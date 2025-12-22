<?php

namespace App\Http\Controllers\Api\Examen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Examen\ExamenRequest;
use App\Http\Requests\Examen\ExamenUpdateRequest;
use App\Models\Examen;
use App\Repository\ExamenRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Examens",
 *     description="Gestion du catalogue des examens et analyses médicales"
 * )
 */
class ExamenController extends Controller
{
    /**
     * @var ExamenRepository
     */
    private $examenRepository;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(ExamenRepository $examenRepository, AuthManager $auth)
    {
        $this->examenRepository = $examenRepository;
        $this->auth = $auth;
    }

    /**
     * @OA\Get(
     *     path="/api/examen/list",
     *     summary="Liste des examens",
     *     tags={"Examens"},
     *     description="Récupère la liste complète des examens disponibles.",
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="listes des Examen"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Examen"))
     *         )
     *     )
     * )
     */
    public function listExamen(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Examen',
            'data' => $this->examenRepository->get_Examen()
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/examen/get",
     *     summary="Détail d'un examen",
     *     tags={"Examens"},
     *     description="Récupère les détails d'un examen spécifique par son ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID de l'examen",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/Examen")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="ID manquant ou invalide"
     *     )
     * )
     */
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:examens,id'],
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
            'message' => 'get Examen',
            'data' => Examen::where('id', $request->id)->first()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/examen/create",
     *     summary="Créer un examen",
     *     tags={"Examens"},
     *     security={{"bearerAuth":{}}},
     *     description="Ajoute un nouvel examen à la base de données. (Admin/Labo)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"label", "price", "laboratorie_id"},
     *             @OA\Property(property="label", type="string", example="Groupe Sanguin"),
     *             @OA\Property(property="price", type="number", example=2500),
     *             @OA\Property(property="description", type="string", example="Détermination du groupe sanguin"),
     *             @OA\Property(property="laboratorie_id", type="integer", example=1),
     *             @OA\Property(property="icon", type="string", example="url_image")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Examen créé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="create Examen"),
     *             @OA\Property(property="data", ref="#/components/schemas/Examen")
     *         )
     *     )
     * )
     */
    public function create(ExamenRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'create Examen',
            'data' => $this->examenRepository->create_Examen($request)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/examen/update",
     *     summary="Mettre à jour un examen",
     *     tags={"Examens"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="label", type="string", example="Nouveau Nom"),
     *             @OA\Property(property="price", type="number", example=3000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Examen mis à jour",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="update Examen"),
     *             @OA\Property(property="data", ref="#/components/schemas/Examen")
     *         )
     *     )
     * )
     */
    public function update(ExamenUpdateRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'update Examen',
            'data' => $this->examenRepository->update_Examen($request, $request->id)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/examen/delete",
     *     summary="Supprimer un examen",
     *     tags={"Examens"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Examen supprimé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="delete Examen")
     *         )
     *     )
     * )
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:examens,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $examen = Examen::where('id', $request->id)->first();
        $examen->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'delete Examen',
        ]);
    }
}
