<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AgendaRequest;
use App\Http\Requests\User\AgendaUpdateRequest;
use App\Models\Agenda;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgendaController extends Controller
{
    /**
     * @var Agenda
     */
    private $agenda;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(AuthManager $auth)
    {
        $this->agenda = new Agenda();
        $this->auth = $auth;
    }

    /**
     * listes Agenda user
     */
    public function listAgenda(Request $request)
    {
        if($request->day){
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'list Agenda du jour',
                'data' => Agenda::where('agent_id', $this->auth->use()->id)
                                ->where('day', $request->day)->get()
            ]);
        }
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'list Agenda',
            'data' => Agenda::where('agent_id', $this->auth->use()->id)->paginate(15)
        ]);
    }


    /**
     * get Agenda
     */
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:Agendas,id'],
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
            'message' => 'get Agenda',
            'data' => Agenda::where('id', $request->id)->first()
        ]);
    }



    /**
     * create Agenda
     */
    public function createAgenda(AgendaRequest $request)
    {
        $this->agenda = Agenda::create([
            'label' =>  $request->label,
            'day'  =>  $request->day,
            'hour'  =>  $request->hour,
            'agent_id'  => $this->auth->user()->id
        ]);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'create Agenda',
            'data' => $this->agenda
        ]);
    }

    /**
     * update Agenda.
     */
    public function updateAgenda(AgendaUpdateRequest $request)
    {
        $this->agenda = Agenda::where('id', $request->id)->first();
        $this->agenda->update([
            'label' =>  $request->label,
            'day'  =>  $request->day,
            'hour'  =>  $request->hour,
            'agent_id'  => $this->auth->user()->id
        ]);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'update Agenda',
            'data' => $this->agenda
        ]);
    }

   
    /**
     * Delete Agenda
     */
    public function deleteAgenda(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:Agendas,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $this->agenda = Agenda::where('id', $request->id)->first();
        $this->agenda->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'delete Agenda',
            // 'data' => $this->Agenda
        ]);
    }
}
