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
     * listes TypeBilan
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
     * get TypeBilan
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
     * create TypeBilan
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
     * update TypeBilan.
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
     * Delete TypeBilan
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
            // 'data' => $this->TypeBilan
        ]);
        
    }
}
