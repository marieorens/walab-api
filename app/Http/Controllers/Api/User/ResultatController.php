<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ResultatRequest;
use App\Http\Requests\User\ResultatUpdateRequest;
use App\Models\Resultat;
use App\Repository\ResultatRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Résultats",
 *     description="Gestion des résultats d'analyses (PDF, Mots de passe)"
 * )
 */
class ResultatController extends Controller
{
    /**
     * @var ResultatRepository
     */
    private $resultatRepository;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(ResultatRepository $resultatRepository, AuthManager $auth)
    {
        $this->resultatRepository = $resultatRepository;
        $this->auth = $auth;
    }

    /**
     * @OA\Get(
     *     path="/api/resultat/list",
     *     summary="Mes résultats",
     *     tags={"Résultats"},
     *     security={{"bearerAuth":{}}},
     *     description="Récupère la liste des résultats d'analyses de l'utilisateur connecté.",
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="listes des Resultats"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Resultat"))
     *         )
     *     )
     * )
     */
    public function listResultat(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Resultats',
            'data' => $this->resultatRepository->get_Resultat_user($this->auth->user()->id)
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/resultat/get",
     *     summary="Détail d'un résultat",
     *     tags={"Résultats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID du résultat",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Resultat")
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
            'id' => ['required', 'integer', 'exists:resultats,id'],
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
            'message' => 'get Resultat',
            'data' => Resultat::where('id', $request->id)->first()
        ]);
    }



    /**
     * @OA\Post(
     *     path="/api/resultat/create",
     *     summary="Uploader un résultat",
     *     tags={"Résultats"},
     *     security={{"bearerAuth":{}}},
     *     description="Ajoute un fichier PDF de résultat pour une commande. (Admin/Labo)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"code_commande", "pdf_url"},
     *                 @OA\Property(property="code_commande", type="string", example="CMD-XXXX", description="Code de la commande liée"),
     *                 @OA\Property(property="pdf_url", type="string", format="binary", description="Fichier PDF (max 10Mo)"),
     *                 @OA\Property(property="pdf_password", type="string", description="Mot de passe optionnel pour le PDF")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Résultat créé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="create Resultat"),
     *             @OA\Property(property="data", ref="#/components/schemas/Resultat")
     *         )
     *     )
     * )
     */
    public function create(ResultatRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'create Resultat',
            'data' => $this->resultatRepository->create_Resultat($request)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/resultat/update",
     *     summary="Mettre à jour un résultat",
     *     tags={"Résultats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"id", "code_commande"},
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="code_commande", type="string", example="CMD-XXXX"),
     *                 @OA\Property(property="pdf_url", type="string", format="binary", description="Nouveau fichier PDF (Optionnel)"),
     *                 @OA\Property(property="pdf_password", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Résultat mis à jour"
     *     )
     * )
     */
    public function update(ResultatUpdateRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'update Resultat',
            'data' => $this->resultatRepository->update_Resultat($request)
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/resultat/delete",
     *     summary="Supprimer un résultat",
     *     tags={"Résultats"},
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
     *         description="Résultat supprimé"
     *     )
     * )
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:resultats,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $resultat = Resultat::where('id', $request->id)->first();
        $resultat->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'delete Resultat',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/resultat/admin/list",
     *     summary="Tous les résultats (Admin)",
     *     tags={"Résultats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste complète pour administration"
     *     )
     * )
     */
    public function listResultatAdmin()
    {

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'list de tout les resultats',
            'data' => Resultat::paginate(15)
        ]);
    }
}
