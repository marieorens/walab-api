<?php

namespace App\Http\Controllers\Api\Paiement;

use App\Enum\StatutPaiementEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Paiement\PaiementRequest;
use App\Http\Requests\Paiement\PaiementUpdateRequest;
use App\Models\Paiement;
use App\Repository\PaiementRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaiementController extends Controller
{
    /**
     * @var PaiementRepository
     */
    private $paiementRepository;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(PaiementRepository $paiementRepository, AuthManager $auth)
    {
        $this->paiementRepository = $paiementRepository;
        $this->auth = $auth;
    }

    /**
     * listes Paiement
     */
    public function listPaiement(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Paiement',
            'data' => $this->paiementRepository->get_Paiement($this->auth->user()->id)
        ]);
    }


    /**
     * get Paiement
     */
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:Paiements,id'],
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
            'message' => 'get Paiement',
            'data' => Paiement::where('id', $request->id)->first()
        ]);
    }

    /**
     * get Paiement par code
     */
    public function get_Paiement_code(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'exists:commandes,code'],
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
            'message' => 'get Paiement par code',
            'data' => $this->paiementRepository->get_Paiement_code($request->code)
        ]);
    }
    

    /**
     * get Paiement par code Commande
     */
    public function get_user()
    {        
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'get list Paiement user',
            'data' => Paiement::where('client_id', $this->auth->user()->id)->first()
        ]);
    }

    /**
     * get Paiement par code Commande
     */
    public function get_code_commande(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_commande' => ['required', 'exists:commandes,code_commande'],
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
            'message' => 'get Paiement',
            'data' => Paiement::where('code_commande', $request->code_commande)->first()
        ]);
    }

    
    /**
     * create Paiement
     */
    public function create(PaiementRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'create Paiement',
            'data' => $this->paiementRepository->init_Paiement($request)
        ]);
    }


    /**
     * create Paiement Manuel
     */
    public function createPaiementManuel(PaiementRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'create Paiement',
            'data' => $this->paiementRepository->createPaiementManuel($request)
        ]);
    }

    /**
     * update Paiement.
     */
    public function update(PaiementUpdateRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'update Paiement',
            'data' => $this->paiementRepository->update_Paiement($request)
        ]);
    }

   
    /**
     * Delete Paiement
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:paiements,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $paiement = Paiement::where('id', $request->id)->first();
        $paiement->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'delete Paiement',
            // 'data' => $this->Paiement
        ]);

    }

    /**
     * Change Statut
     */
    public function changeStatut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_commande' => ['required', 'string'],
            'statut' => [Rule::enum(StatutPaiementEnum::class)],
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
            'message' => 'change statut Paiement',
            'data' => $this->paiementRepository->change_statut($request->code_commande, $request->statut)
        ]);

    }

    /**
     * list paiement commande Admin
     */
    public function listPaiementAllAdmin()
    {

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'list paiement commande Admin',
            'data' => Paiement::paginate(15)
        ]);
    }
}
