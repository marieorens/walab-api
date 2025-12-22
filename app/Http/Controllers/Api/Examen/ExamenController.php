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
     * listes Examen
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
     * get Examen
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
     * create Examen
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
     * update Examen.
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
     * Delete Examen
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
            // 'data' => $this->Examen
        ]);

    }
}
