<?php

namespace App\Repository;

use App\Enum\StatutPaiementEnum;
use App\Http\Requests\Paiement\PaiementRequest;
use App\Models\Commande;
use App\Models\Paiement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaiementRepository
{

    /**
     * @var Paiement
     */
    private $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function init_Paiement(Request $request){

        $paiement = $this->paiement->newQuery()->create([
            
            'montant' => $request->montant,
            'code_commande'  => $request->code_commande,
            'transaction_id'  => $request->transaction_id,
            'status'  => StatutPaiementEnum::PENDING

        ]);

        return $paiement;
    }

    public function createPaiementManuel(Request $request){

        $paiement = $this->paiement->newQuery()->create([
            'montant' => $request->montant,
            'code_commande'  => $request->code_commande,
            'transaction_id'  => 'manuel',
            'reference' => isset($request->reference) ? $request->reference : 'manuel',
            'mode' => isset($request->mode) ? $request->mode : 'invalid',
            'status'  => StatutPaiementEnum::PAYER

        ]);

        $commandes = Commande::where('code', $request->code_commande)->get();
        foreach($commandes as $commande){
            $commande->montant = 0;
            $commande->save();
        }

        return $paiement;
    }

    public function create_paiement($montant, $code, $transaction_id, $reference, $mode, $status){

        $paiement = $this->paiement->newQuery()->create([
            
            'montant' => $montant,
            'code_commande'  => $code,
            'transaction_id' => $transaction_id,
            'reference' => isset($reference) ? $reference : null,
            'mode' => isset($mode) ? $mode : 'invalid',
            'status'  => $status,

        ]);

        return $paiement;
    }

    public function change_statut(string $code, StatutPaiementEnum $statut){

        $paiement = Paiement::where('code', $code)
                    ->orWhere('id', $code)->first();
        
        $paiement->update([

            'status'  => $statut

        ]);

        return $paiement;
    }

    public function update_Paiement(Request $request){

        $paiement = Paiement::where('id', $request->id)->first();
        
        $paiement->update([
            'montant' => $request->montant,
            'code_commande'  => $request->code_commande,
            'transaction_id'  => $request->transaction_id,
            'status'  => StatutPaiementEnum::PENDING
        ]);

        $paiement->save();

        return $paiement;
    }

    public function get_Paiement(int $user_id){
        $codes = Commande::select("code")
            ->where('client_id', $user_id)
            ->groupBy("code")
            ->get();
        return $this->paiement->newQuery()
            ->whereIn('code_commande', $codes)
            // ->whith()
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
    }

    public function get_Paiement_code(string $code){
        return $this->paiement->newQuery()
        ->where('code_commande', $code)
        ->get();
    }

}