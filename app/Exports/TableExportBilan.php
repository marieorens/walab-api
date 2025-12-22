<?php

namespace App\Exports;

use App\Models\TypeBilan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TableExportBilan implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TypeBilan::with('laboratorie')->get()->map(function($bilan) {
            return [
                'label' => $bilan->label,
                'price' => $bilan->price,
                'description' => $bilan->description,
                'laboratoire' => $bilan->laboratorie ? $bilan->laboratorie->name : 'N/A',
                'created_at' => $bilan->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nom du bilan',
            'Prix (FCFA)',
            'Description',
            'Laboratoire',
            'Date de création',
        ];
    }
}
