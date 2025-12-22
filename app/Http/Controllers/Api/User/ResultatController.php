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
     * listes Resultat
     */
    public function listResultat(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Resultats',
            'data' => $this->resultatRepository->get_Resultat($this->auth->user()->id)
        ]);
    }


    /**
     * get Resultat
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
     * create Resultat
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
     * update Resultat.
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
     * Delete Resultat
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
            // 'data' => $this->Resultat
        ]);

    }

    /**
     * list Resultat Admin
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
