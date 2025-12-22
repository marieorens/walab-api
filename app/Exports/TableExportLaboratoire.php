<?php

namespace App\Exports;

use App\Models\Laboratorie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TableExportLaboratoire implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Laboratorie::with('user')
            ->where('isdelete', false)
            ->get()
            ->map(function($lab) {
                return [
                    'name' => $lab->name,
                    'address' => $lab->address,
                    'description' => $lab->description,
                    'contact_email' => $lab->user->email ?? 'N/A',
                    'contact_phone' => $lab->user->phone ?? 'N/A',
                    'status' => $lab->status,
                    'created_at' => $lab->created_at,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nom du laboratoire',
            'Adresse',
            'Description',
            'Email de contact',
            'Téléphone',
            'Statut',
            'Date de création',
        ];
    }
}
