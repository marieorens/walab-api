<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TableExportAdmin implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::whereIn('role_id', [1, 4])->select('firstname', 'lastname', 'email', 'phone', 'gender', 'date_naissance', 'city', 'adress', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Prenoms',
            'Email',
            'Téléphone',
            'Sex',
            'Date de naissance',
            'Ville',
            'Addresse',
            'Date de création',
        ];
    }
}
