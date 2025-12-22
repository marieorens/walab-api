<?php

namespace App\Repository;

use App\Enum\StatutCommandeEnum;
use App\Http\Requests\User\ResultatRequest;
use App\Jobs\CreatePdfProtect;
use App\Models\Commande;
use App\Models\DaTaNotification;
use App\Models\Resultat;
use App\Models\User;
use App\Notifications\ResultatNotification;
use App\Notifications\SendPushNotification; // <--- IMPORT AJOUTÉ
use App\Services\SendMailService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use DevRaeph\PDFPasswordProtect\Facade\PDFPasswordProtect;
use Smalot\PdfParser\Parser;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;

class ResultatRepository
{
    private $resultat;
    protected $sendEmail;

    public function __construct(Resultat $resultat)
    {
        $this->resultat = $resultat;
        $this->sendEmail = new SendMailService();
    }

    // Assurez-vous d'avoir les imports nécessaires en haut de votre fichier (Commande, StatutCommandeEnum, Str, etc.)

    public function create_Resultat(Request $request)
    {
        if($request->id){
            return $this->update_Resultat($request);
        }
        else{
            $pdfPassword = Str::random(10);

            $pdf_url = time() . $request->pdf_url->getClientOriginalName();

            $path = $request->pdf_url->move(public_path() . "/resultat", $pdf_url);
            $path = "resultat/" . $pdf_url;

            $filePath = public_path($path);
            $userPassword = '123456abcd';


            $resultat = $this->resultat->newQuery()->create([
                'pdf_url' => $path,
                'code_commande'  => $request->code_commande,
                'pdf_password' => $pdfPassword,
            ]);

            $commandes = Commande::where('code', $request->code_commande)->get();

            $commissionService = app(\App\Services\CommissionService::class);
            foreach ($commandes as $commande) {
                if ($commande->statut != StatutCommandeEnum::FINISH) {
                    $commande->statut = StatutCommandeEnum::FINISH;
                    $commande->save();
                    
                    $commissionService->creditForCommande($commande);
                }
            }

            $this->notifyClientAndAgent($resultat, $request->code_commande);

            return $resultat;
        }
    }


    public function update_Resultat(Request $request){

        $pdfPassword = Str::random(10);
        $resultat = Resultat::where('id', $request->id)->first(); // Correction fisrt -> first

        if($request->pdf_url){
            $pdf_url = time() . $request->pdf_url->getClientOriginalName();
            $path = $request->pdf_url->move(public_path() . "/resultat", $pdf_url);
            $path = "resultat/" . $pdf_url;

            $filePath = public_path($path);
            $userPassword = '123456abcd';

            $this->protectPdf($filePath, $filePath, $pdfPassword, $userPassword);
        }
        else{
            $path = $resultat->pdf_url;
        }

        $resultat->update([
            'pdf_url' => $path,
            'code_commande'  => $request->code_commande,
            'pdf_password' => $pdfPassword,
        ]);

        $resultat->save();

        $commissionService = app(\App\Services\CommissionService::class);
        $commandes->map(function ($query) use ($commissionService) {
            if ($query->statut != StatutCommandeEnum::FINISH) {
                $query->statut = StatutCommandeEnum::FINISH;
                $query->save();

                $commissionService->creditForCommande($query);
            }
            return $query;
        });

        $this->notifyClientAndAgent($resultat, $request->code_commande);

        return $resultat;
    }

    /**
     * Méthode centralisée pour notifier (Push + Mail + DB)
     */
    private function notifyClientAndAgent($resultat, $codeCommande)
    {
        $commande = Commande::where('code', $codeCommande)->first();
        if(!$commande) return;

        $codeOuverture = $resultat->pdf_password;
        $lienFichier = url($resultat->pdf_url);

        $messageLong = "Le résultat de la commande : " . $commande->code . " est disponible. Vous pouvez accéder au fichier PDF [ici]($lienFichier). Veuillez utiliser le code suivant pour ouvrir le fichier : **$codeOuverture**.";

        
        $this->sendEmail->sendMail($commande->client->id, $messageLong);

        try {
            $client = User::find($commande->client_id);
            if ($client) {
                $client->notify(new SendPushNotification(
                    'Résultats Disponibles ✅', // Titre accrocheur
                    'Vos analyses pour la commande #' . $commande->code . ' sont prêtes. Cliquez pour télécharger.',
                    '/user/details/commande/' . $commande->code,
                    'Télécharger PDF'
                ));
            }
        } catch (\Exception $e) {
            Log::error("Erreur Push Résultat : " . $e->getMessage());
        }
    }

    public function get_Resultat(string $code){
        return $this->resultat->newQuery()
            ->where('code_commande', $code)
            ->orderBy('created_at', 'DESC')
            ->paginate(15);
    }

    public function get_Resultat_user(int $user_id){
        return $this->resultat->newQuery()
            ->orderBy('created_at', 'DESC')
            ->paginate(15);
    }

    public function extractAndProtectPDF($filePath, $pdfPassword, $userPassword)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();

        $mpdf = new Mpdf(['tempDir' => $this->getMpdfTempDir()]);
        $mpdf->WriteHTML('<h1>Extracted Content from PDF</h1>');
        $mpdf->WriteHTML('<pre>' . htmlspecialchars($text) . '</pre>');
        $mpdf->SetProtection(['print', 'copy'], $pdfPassword, $userPassword);
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);
    }

    public function protectPdf($filePath, $outputPath, $userPassword, $ownerPassword = null)
    {
        $mode = 'utf-8';
        $format = 'auto';
        $pageSize = $this->getPageSize($filePath);

        $export = new \Mpdf\Mpdf([
            'mode' => $mode,
            'format' => ($format == 'auto')
                ? [$pageSize['width'], $pageSize['height']]
                : (($pageSize['width'] < $pageSize['height']) ? $format : $format . '-L'),
            'tempDir' => $this->getMpdfTempDir(),
        ]);

        $pdfContent = file_get_contents($filePath);
        $tempInputFile = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempInputFile, $pdfContent);

        $pagecount = $export->setSourceFile($tempInputFile);

        for ($p = 1; $p <= $pagecount; $p++) {
            $tplId = $export->importPage($p);
            $wh = $export->getTemplateSize($tplId);
            $export->state = $p == 1 ? 0 : 1;
            $export->AddPage($wh['width'] > $wh['height'] ? 'L' : 'P');
            $export->UseTemplate($tplId);
        }

        unlink($tempInputFile);
        $export->SetProtection(['copy', 'print'], $userPassword, $ownerPassword);
        $export->Output($outputPath, \Mpdf\Output\Destination::FILE);
    }

    private function getPageSize($inputFile): array
    {
        $mpdf = new \Mpdf\Mpdf(['tempDir' => $this->getMpdfTempDir()]);
        $pdfContent = file_get_contents($inputFile);
        $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempFile, $pdfContent);

        $mpdf->setSourceFile($tempFile);
        $getFirstPage = $mpdf->importPage(1);
        unlink($tempFile);

        return $mpdf->getTemplateSize($getFirstPage);
    }

    private function getMpdfTempDir(): string
    {
        $baseDir = storage_path('app/tmp');
        if (!File::exists($baseDir)) {
            File::makeDirectory($baseDir, 0777, true);
        } else {
            @chmod($baseDir, 0777);
        }

        $mpdfDir = $baseDir . '/mpdf';
        if (!File::exists($mpdfDir)) {
            File::makeDirectory($mpdfDir, 0777, true);
        } else {
            @chmod($mpdfDir, 0777);
        }

        $mpdfSubDir = $mpdfDir . '/mpdf';
        if (!File::exists($mpdfSubDir)) {
            File::makeDirectory($mpdfSubDir, 0777, true);
        } else {
            @chmod($mpdfSubDir, 0777);
        }

        return $baseDir;
    }
}
