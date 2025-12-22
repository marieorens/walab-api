<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    /**
     * @var Role
     */
    private $role;

    public function __construct()
    {
        $this->role = new Role();
    }

    /**
     * listes Role
     */
    public function list()
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'list role',
            'data' => Role::all()
        ]);
    }


    /**
     * get Role
     */
    public function getRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:roles,id'],
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
            'message' => 'get role',
            'data' => Role::where('id', $request->id)->first()
        ]);
    }



    /**
     * create Role
     */
    public function create(RoleRequest $request)
    {
        $this->role = Role::create([
            'label' => $request->label,
            'value' => $request->value,
        ]);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'create role',
            'data' => $this->role
        ]);
    }

    /**
     * update Role.
     */
    public function update(RoleUpdateRequest $request)
    {
        $this->role = Role::where('id', $request->id)->first();
        $this->role->update([
            'label' => $request->label,
            'value' => $request->value,
        ]);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'update role',
            'data' => $this->role
        ]);
    }

   
    /**
     * Delete Role
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:roles,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $this->role = Role::where('id', $request->id)->first();
        $this->role->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'delete role',
            // 'data' => $this->role
        ]);
    }

}
