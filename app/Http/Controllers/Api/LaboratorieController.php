<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LaboratorieRequest;
use App\Http\Requests\LaboratorieUpdateRequest;
use App\Models\Laboratorie;
use App\Repository\LaboratorieRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaboratorieController extends Controller
{
    /**
     * @var LaboratorieRepository
     */
    private $laboratorieRepository;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(LaboratorieRepository $laboratorieRepository, AuthManager $auth)
    {
        $this->laboratorieRepository = $laboratorieRepository;
        $this->auth = $auth;
    }

    /**
     * listes Laboratorie
     */
    public function listLaboratorie(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Laboratorie',
            'data' => $this->laboratorieRepository->get_laboratorie()->paginate(10)
        ]);
    }


    /**
     * get Laboratorie
     */
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:laboratories,id'],
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
            'message' => 'get Laboratorie',
            'data' => Laboratorie::where('id', $request->id)->first()
        ]);
    }


    /**
     * get examens du laboratoire
     */
    public function getExamens(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:laboratories,id'],
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
            'message' => 'get examens',
            'data' => $this->laboratorieRepository->get_examens($request->id)->paginate(10)
        ]);
    }


    /**
     * get Type bilan du Laboratorie
     */
    public function getBilans(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:laboratories,id'],
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
            'message' => 'get bilans',
            'data' => $this->laboratorieRepository->get_bilans($request->id)->paginate(15)
        ]);
    }



    /**
     * create Laboratorie
     */
    public function create(LaboratorieRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'create Laboratorie',
            'data' => $this->laboratorieRepository->create_laboratorie($request)
        ]);
    }

    /**
     * update Laboratorie.
     */
    public function update(LaboratorieUpdateRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'update Laboratorie',
            'data' => $this->laboratorieRepository->update_laboratorie($request, $request->id)
        ]);
    }

   
    /**
     * Delete Laboratorie
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:laboratories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $Laboratorie = Laboratorie::where('id', $request->id)->first();
        $Laboratorie->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'delete Laboratorie',
            // 'data' => $this->Laboratorie
        ]);

    }
}
