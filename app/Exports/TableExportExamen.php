<?php

namespace App\Exports;

use App\Models\Examen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TableExportExamen implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Examen::with('laboratorie')->get()->map(function($examen) {
            return [
                'label' => $examen->label,
                'price' => $examen->price,
                'description' => $examen->description,
                'laboratoire' => $examen->laboratorie ? $examen->laboratorie->name : 'N/A',
                'created_at' => $examen->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nom de l\'examen',
            'Prix (FCFA)',
            'Description',
            'Laboratoire',
            'Date de création',
        ];
    }
}
