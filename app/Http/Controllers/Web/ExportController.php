<?php

namespace App\Http\Controllers\Web;

use App\Exports\TableExportAgent;
use App\Exports\TableExportClient;
use App\Exports\TableExportPractitioner;
use App\Exports\TableExportLaboratoire;
use App\Exports\TableExportAdmin;
use App\Exports\TableExportExamen;
use App\Exports\TableExportBilan;
use App\Exports\TableExportCommande;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exporter_agent()
    {
        return Excel::download(new TableExportAgent, 'listes_agent_'. Carbon::now() . '.xlsx');
    }

    public function exporter_client()
    {
        return Excel::download(new TableExportClient, 'listes_client_'. Carbon::now() . '.xlsx');
    }

    public function exporter_practitioner()
    {
        return Excel::download(new TableExportPractitioner, 'listes_praticien_'. Carbon::now() . '.xlsx');
    }

    public function exporter_laboratoire()
    {
        return Excel::download(new TableExportLaboratoire, 'listes_laboratoire_'. Carbon::now() . '.xlsx');
    }

    public function exporter_admin()
    {
        return Excel::download(new TableExportAdmin, 'listes_admin_'. Carbon::now() . '.xlsx');
    }

    public function exporter_examen()
    {
        return Excel::download(new TableExportExamen, 'listes_examen_'. Carbon::now() . '.xlsx');
    }

    public function exporter_bilan()
    {
        return Excel::download(new TableExportBilan, 'listes_bilan_'. Carbon::now() . '.xlsx');
    }

    public function exporter_commande()
    {
        return Excel::download(new TableExportCommande, 'listes_commande_'. Carbon::now() . '.xlsx');
    }
}
