<?php

namespace App\Exports;

use App\Models\Commande;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TableExportCommande implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Commande::with(['client', 'laboratorie', 'agent'])->get()->map(function($commande) {
            return [
                'code_commande' => $commande->code_commande,
                'client' => $commande->client ? $commande->client->firstname . ' ' . $commande->client->lastname : 'N/A',
                'laboratoire' => $commande->laboratorie ? $commande->laboratorie->name : 'N/A',
                'agent' => $commande->agent ? $commande->agent->firstname . ' ' . $commande->agent->lastname : 'N/A',
                'montant' => $commande->montant,
                'statut' => $commande->statut,
                'created_at' => $commande->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Code commande',
            'Client',
            'Laboratoire',
            'Agent',
            'Montant (FCFA)',
            'Statut',
            'Date de création',
        ];
    }
}
