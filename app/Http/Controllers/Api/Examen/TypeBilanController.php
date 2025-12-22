<?php

namespace App\Http\Controllers\Api\Examen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Examen\TypeBilanRequest;
use App\Http\Requests\Examen\TypeBilanUpdateRequest;
use App\Models\TypeBilan;
use App\Repository\TypeBilanRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="TypeBilan",
 *     description="Gestion des bilans médicaux (Packages d'examens)"
 * )
 */
class TypeBilanController extends Controller
{
    /**
     * @var TypeBilanRepository
     */
    private $typeBilanRepository;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(TypeBilanRepository $typeBilanRepository, AuthManager $auth)
    {
        $this->typeBilanRepository = $typeBilanRepository;
        $this->auth = $auth;
    }

    /**
     * @OA\Get(
     *     path="/api/typebilan/list",
     *     summary="Liste des bilans",
     *     tags={"TypeBilan"},
     *     description="Récupère la liste complète des types de bilans disponibles.",
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="listes des TypeBilan"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TypeBilan"))
     *         )
     *     )
     * )
     */
    public function listTypeBilan(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des TypeBilan',
            'data' => $this->typeBilanRepository->get_TypeBilan()
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/typebilan/get",
     *     summary="Détail d'un bilan",
     *     tags={"TypeBilan"},
     *     description="Récupère les détails d'un bilan spécifique par son ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID du bilan",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/TypeBilan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="ID invalide"
     *     )
     * )
     */
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:type_bilans,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'get TypeBilan',
            'data' => TypeBilan::where('id', $request->id)->first()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/typebilan/create",
     *     summary="Créer un bilan",
     *     tags={"TypeBilan"},
     *     security={{"bearerAuth":{}}},
     *     description="Ajoute un nouveau type de bilan. (Admin/Labo)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"label", "price", "laboratorie_id"},
     *             @OA\Property(property="label", type="string", example="Bilan Prénuptial"),
     *             @OA\Property(property="price", type="number", example=20000),
     *             @OA\Property(property="description", type="string", example="Description du bilan"),
     *             @OA\Property(property="laboratorie_id", type="integer", example=1),
     *             @OA\Property(property="icon", type="string", example="image_url")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bilan créé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="create TypeBilan"),
     *             @OA\Property(property="data", ref="#/components/schemas/TypeBilan")
     *         )
     *     )
     * )
     */
    public function create(TypeBilanRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'create TypeBilan',
            'data' => $this->typeBilanRepository->create_TypeBilan($request)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/typebilan/update",
     *     summary="Mettre à jour un bilan",
     *     tags={"TypeBilan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="label", type="string", example="Nouveau Nom Bilan"),
     *             @OA\Property(property="price", type="number", example=22000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="update TypeBilan")
     *         )
     *     )
     * )
     */
    public function update(TypeBilanUpdateRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'update TypeBilan',
            'data' => $this->typeBilanRepository->update_TypeBilan($request, $request->id)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/typebilan/delete",
     *     summary="Supprimer un bilan",
     *     tags={"TypeBilan"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="delete TypeBilan")
     *         )
     *     )
     * )
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:type_bilans,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $typeBilan = TypeBilan::where('id', $request->id)->first();
        $typeBilan->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'delete TypeBilan',
        ]);
    }
}
