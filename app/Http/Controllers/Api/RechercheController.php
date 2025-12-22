<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\TypeBilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RechercheController extends Controller
{

    /**
     * Recherche et filtrage des examens et bilans
     * Retourne les laboratoires qui proposent au moins 1 des éléments filtrés
     */
    public function searchExamenBilan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => ['nullable', 'string'],
                'type' => ['nullable', 'string', 'in:examen,bilan,all'],
                'examen_ids' => ['nullable', 'array'],
                'examen_ids.*' => ['integer', 'exists:examens,id'],
                'price_min' => ['nullable', 'numeric', 'min:0'],
                'price_max' => ['nullable', 'numeric', 'min:0'],
                'isactive' => ['nullable'],
                'laboratorie_id' => ['nullable', 'integer', 'exists:laboratories,id'],
                'address' => ['nullable', 'string'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
                'page' => ['nullable', 'integer', 'min:1'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $type = $request->input('type', 'all');
            $search = $request->input('search');
            $perPage = (int) $request->input('per_page', 10);
            $currentPage = (int) $request->input('page', 1);

            // Debug: Log les paramètres reçus
            Log::info('Search params:', [
                'type' => $type,
                'search' => $search,
                'filters' => $request->except(['page', 'per_page', 'type', 'search'])
            ]);

            $filteredItems = collect();

            // Récupérer les examens et/ou bilans filtrés
            if ($type === 'bilan') {
                $filteredItems = $this->getBilans($request, $search);
            } elseif ($type === 'examen') {
                $filteredItems = $this->getExamens($request, $search);
            } else {
                $bilans = $this->getBilans($request, $search);
                $examens = $this->getExamens($request, $search);
                $filteredItems = $bilans->concat($examens);
            }

            // Debug: Log le nombre d'items trouvés
            Log::info('Filtered items count:', ['count' => $filteredItems->count()]);

            // Extraire les IDs de laboratoires uniques
            $laboratoryIds = $filteredItems->pluck('laboratorie_id')->unique()->values();

            // Si aucun service trouvé, retourner liste vide paginée
            if ($laboratoryIds->isEmpty()) {
                $emptyPaginator = $this->paginateCollection(collect(), $perPage, $currentPage);
                
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => 'Aucun laboratoire ne propose ces services',
                    'data' => $emptyPaginator,
                ]);
            }

            // Récupérer les laboratoires avec leurs examens et bilans correspondants
            $laboratories = DB::table('laboratories')
                ->select('id', 'name', 'address', 'created_at')
                ->whereIn('id', $laboratoryIds)
                ->get()
                ->map(function ($lab) use ($filteredItems) {
                    // Récupérer les examens de ce labo
                    $labExamens = $filteredItems
                        ->where('laboratorie_id', $lab->id)
                        ->where('type', 'examen')
                        ->values();
                    
                    // Récupérer les bilans de ce labo
                    $labBilans = $filteredItems
                        ->where('laboratorie_id', $lab->id)
                        ->where('type', 'bilan')
                        ->values();

                    return [
                        'id' => $lab->id,
                        'name' => $lab->name,
                        'address' => $lab->address,
                        'created_at' => $lab->created_at,
                        'examens_count' => $labExamens->count(),
                        'bilans_count' => $labBilans->count(),
                        'total_services' => $labExamens->count() + $labBilans->count(),
                        'examens' => $labExamens,
                        'bilans' => $labBilans,
                    ];
                })
                ->sortByDesc('total_services')
                ->values();

            // Pagination manuelle
            $paginatedResults = $this->paginateCollection($laboratories, $perPage, $currentPage);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Recherche laboratoires par services',
                'data' => $paginatedResults,
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur searchExamenBilan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'Erreur serveur lors de la recherche',
                'data' => null
            ], 500);
        }
    }

    /**
     * Récupérer la liste des examens distincts pour le filtre
     */
    public function getDistinctExamens(Request $request)
    {
        try {
            $examens = Examen::select('id', 'label')
                ->when($request->has('isactive'), function ($query) use ($request) {
                    $query->where('isactive', filter_var($request->input('isactive'), FILTER_VALIDATE_BOOLEAN));
                })
                ->distinct()
                ->orderBy('label', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Liste des examens disponibles',
                'data' => $examens,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur getDistinctExamens: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'Erreur serveur: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Récupérer les options de filtres disponibles
     */
    public function getFilterOptions()
    {
        try {
            // Récupérer les examens distincts
            $examens = Examen::select('id', 'label')
                ->distinct()
                ->orderBy('label', 'asc')
                ->get();

            // Récupérer les plages de prix avec valeurs par défaut
            $examenMinPrice = Examen::min('price');
            $examenMaxPrice = Examen::max('price');
            $bilanMinPrice = TypeBilan::min('price');
            $bilanMaxPrice = TypeBilan::max('price');

            $priceRange = [
                'min' => min($examenMinPrice ?? 0, $bilanMinPrice ?? 0),
                'max' => max($examenMaxPrice ?? 100000, $bilanMaxPrice ?? 100000),
            ];

            // Récupérer les laboratoires distincts
            $laboratories = DB::table('laboratories')
                ->select('id', 'name', 'address')
                ->get();

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Options de filtres disponibles',
                'data' => [
                    'types' => [
                        ['value' => 'all', 'label' => 'Tous'],
                        ['value' => 'examen', 'label' => 'Analyses'],
                        ['value' => 'bilan', 'label' => 'Bilans'],
                    ],
                    'examens' => $examens,
                    'price_range' => $priceRange,
                    'laboratories' => $laboratories,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur getFilterOptions: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => 'Erreur serveur: ' . $e->getMessage(),
                'data' => [
                    'types' => [],
                    'examens' => [],
                    'price_range' => ['min' => 0, 'max' => 100000],
                    'laboratories' => [],
                ]
            ], 500);
        }
    }

    /**
     * Récupérer les bilans avec filtres
     */
    protected function getBilans(Request $request, ?string $search)
    {
        $query = TypeBilan::select(
            'id',
            'label',
            'laboratorie_id',
            'icon',
            'price',
            'description',
            'isactive',
            'created_at'
        )
        ->selectRaw("'bilan' as type");

        // Filtre recherche textuelle
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filtre par prix minimum
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->input('price_min'));
        }

        // Filtre par prix maximum
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }

        // Filtre par statut actif (colonne isactive sans underscore)
        if ($request->filled('isactive')) {
            $isActive = filter_var($request->input('isactive'), FILTER_VALIDATE_BOOLEAN);
            $query->where('isactive', $isActive);
        }

        // Filtre par laboratoire
        if ($request->filled('laboratorie_id')) {
            $query->where('laboratorie_id', (int) $request->input('laboratorie_id'));
        }

        // Filtre par adresse 
        if ($request->filled('address')) {
            $address = $request->input('address');
            $query->whereHas('laboratorie', function ($q) use ($address) {
                $q->where('address', 'like', '%' . $address . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    // Récupérer les examens avec filtres
    protected function getExamens(Request $request, ?string $search)
    {
        $query = Examen::select(
            'id',
            'label',
            'laboratorie_id',
            'icon',
            'price',
            'description',
            'isactive',
            'created_at'
        )
        ->selectRaw("'examen' as type");

        // Filtre recherche textuelle
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filtre par IDs spécifiques d'examens
        if ($request->filled('examen_ids')) {
            $query->whereIn('id', $request->input('examen_ids'));
        }

        // Filtre par prix minimum
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->input('price_min'));
        }

        // Filtre par prix maximum
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }

        // Filtre par statut actif 
        if ($request->filled('isactive')) {
            $isActive = filter_var($request->input('isactive'), FILTER_VALIDATE_BOOLEAN);
            $query->where('isactive', $isActive);
        }

        // Filtre par laboratoire
        if ($request->filled('laboratorie_id')) {
            $query->where('laboratorie_id', (int) $request->input('laboratorie_id'));
        }

        // Filtre par adresse 
        if ($request->filled('address')) {
            $address = $request->input('address');
            $query->whereHas('laboratorie', function ($q) use ($address) {
                $q->where('address', 'like', '%' . $address . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Paginer une collection manuellement
     */
    protected function paginateCollection($collection, $perPage, $currentPage)
    {
        $total = $collection->count();
        $results = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($results, $total, $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }
}