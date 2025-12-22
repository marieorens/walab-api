<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Examen;
use App\Models\Resultat;
use App\Models\TypeBilan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $table = $request->input('table');
        $query = $request->input('query');
        if($table == "resultats"){
            $results = DB::table("resultats")->where('code_commande', 'LIKE', "%{$query}%")->where('isdelete', false)->limit(5)->get();
            return response()->json($results);
        }elseif($table == "commandes"){
            $results = DB::table("commandes")->
            where('type', 'LIKE', "%{$query}%")->where('isdelete', false)
            ->orwhere('adress', 'like', '%' . $request->search. '%')
            ->limit(5)->get();
            $results->map(function ($query) {
                $query->client = User::where('id', $query->client_id)->first();
                $query->agent = User::where('id', $query->client_id)->first();
                $query->examen = Examen::where('id', $query->examen_id)->first();
                $query->type_bilan = TypeBilan::where('id', $query->type_bilan_id)->first();
                $query->resultat = Resultat::where('code_commande', $query->code)->first();
                return $query;
            });
            return response()->json($results);
        }elseif($table == "laboratories"){
            $results = DB::table("laboratories")->where('name', 'LIKE', "%{$query}%")
            ->where('isdelete', false)->limit(5)->get();
            return response()->json($results);
        }elseif($table == "users_client"){
            $results = DB::table("users")
                ->where('role_id', 3)
                ->where('isdelete', false)
                ->where(function($q) use ($query) {
                    $q->where('firstname', 'LIKE', "%{$query}%")
                      ->orWhere('lastname', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->limit(5)->get();
            return response()->json($results);
        }elseif($table == "users_agent"){
            $results = DB::table("users")
                ->where('role_id', 2)
                ->where('isdelete', false)
                ->where(function($q) use ($query) {
                    $q->where('firstname', 'LIKE', "%{$query}%")
                      ->orWhere('lastname', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->limit(5)->get();
            return response()->json($results);
        }elseif($table == "users_admin"){
            $results = DB::table("users")
                ->where('role_id', 1)
                ->where('isdelete', false)
                ->where(function($q) use ($query) {
                    $q->where('firstname', 'LIKE', "%{$query}%")
                      ->orWhere('lastname', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->limit(5)->get();
            return response()->json($results);
        }elseif($table == "users_practitioner"){
            $results = DB::table("users")
                ->where('role_id', 6)
                ->where('isdelete', false)
                ->where(function($q) use ($query) {
                    $q->where('firstname', 'LIKE', "%{$query}%")
                      ->orWhere('lastname', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->limit(5)->get();
            return response()->json($results);
        }
        else{
            $results = DB::table($table)->where('label', 'LIKE', "%{$query}%")
            ->where('isdelete', false)
            ->orwhere('description', 'like', '%' . $request->search. '%')
            ->limit(5)->get();
            return response()->json($results);
        }
    }
}
