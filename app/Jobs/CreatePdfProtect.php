<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Smalot\PdfParser\Parser;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class CreatePdfProtect implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $filePath, public $pdfPassword, public $userPassword)
    {
        $this->filePath = $filePath; 
        $this->pdfPassword = $pdfPassword;
        $this->userPassword = $userPassword;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // $this->extractAndProtectPDF($this->filePath, $this->pdfPassword, $this->userPassword);
            $this->protectPdf($this->filePath, $this->filePath, $this->pdfPassword, $this->userPassword);
        } catch (\Exception $e) {
            Log::error('Pdf securise : ' . $e->getMessage()
            );
        }
    }

    public function extractAndProtectPDF($filePath, $pdfPassword, $userPassword)
    {
        $parser = new Parser();
        
        $pdf = $parser->parseFile($filePath);
        
        $text = $pdf->getText();

        $mpdf = new Mpdf([
            'tempDir' => $this->getMpdfTempDir(),
        ]);

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

        $tempInputFile = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempInputFile, $filePath);

        $pagecount = $export->setSourceFile($tempInputFile);
        for ($p = 1; $p <= $pagecount; $p++) {
            $tplId = $export->importPage($p);
            $wh = $export->getTemplateSize($tplId);
            $export->state = $p == 1 ? 0 : 1;
            $export->AddPage($wh['width'] > $wh['height'] ? 'L' : 'P');
            $export->UseTemplate($tplId);
        }
        unlink($tempInputFile);

        $ownerPassword = $ownerPassword;
        $export->SetProtection(['copy', 'print'], $userPassword, $ownerPassword);

        // $tempOutputFile = tempnam(sys_get_temp_dir(), 'pdf');
        $export->Output($outputPath, \Mpdf\Output\Destination::FILE);

    }

    private function getPageSize($inputFile): array
    {
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => $this->getMpdfTempDir(),
        ]);

        $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempFile, $inputFile);

        $mpdf->setSourceFile($tempFile);
        $getFirstPage = $mpdf->importPage(1);
        unlink($tempFile);

        return $mpdf->getTemplateSize($getFirstPage);
    }

    /**
     * Get the temporary directory for mPDF and create it if it doesn't exist
     * mPDF will create a subdirectory 'mpdf' inside this directory
     */
    private function getMpdfTempDir(): string
    {
        $baseDir = storage_path('app/tmp');
        
        // Create base directory if it doesn't exist with writable permissions
        if (!File::exists($baseDir)) {
            File::makeDirectory($baseDir, 0777, true);
        } else {
            // Ensure the directory is writable
            @chmod($baseDir, 0777);
        }
        
        // Create mPDF subdirectory that mPDF will use
        $mpdfDir = $baseDir . '/mpdf';
        if (!File::exists($mpdfDir)) {
            File::makeDirectory($mpdfDir, 0777, true);
        } else {
            @chmod($mpdfDir, 0777);
        }
        
        // mPDF creates another 'mpdf' subdirectory inside, so create it in advance
        $mpdfSubDir = $mpdfDir . '/mpdf';
        if (!File::exists($mpdfSubDir)) {
            File::makeDirectory($mpdfSubDir, 0777, true);
        } else {
            @chmod($mpdfSubDir, 0777);
        }
        
        // Return the base directory - mPDF will use mpdf/mpdf inside it
        return $baseDir;
    }

}
