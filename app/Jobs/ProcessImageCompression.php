<?php

namespace App\Jobs;

use App\Services\UploadService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessImageCompression implements ShouldQueue
{
    use Queueable;

    protected string $filePath;
    protected string $originalPath;
    protected array $compressionOptions;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, string $originalPath = null, array $compressionOptions = [])
    {
        $this->filePath = $filePath;
        $this->originalPath = $originalPath ?? $filePath;
        $this->compressionOptions = array_merge([
            'quality' => 85,
            'max_width' => 1920,
            'max_height' => 1080,
        ], $compressionOptions);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info("Début de la compression d'image : {$this->filePath}");

            // Vérifier que le fichier existe
            if (!Storage::exists($this->filePath)) {
                Log::warning("Fichier introuvable pour compression : {$this->filePath}");
                return;
            }

            // Obtenir le contenu du fichier
            $fileContent = Storage::get($this->filePath);
            $tempPath = tempnam(sys_get_temp_dir(), 'compress_');

            // Sauvegarder temporairement
            file_put_contents($tempPath, $fileContent);

            // Créer une image Intervention
            $image = \Intervention\Image\Facades\Image::make($tempPath);

            // Redimensionner si nécessaire
            if ($image->width() > $this->compressionOptions['max_width'] ||
                $image->height() > $this->compressionOptions['max_height']) {

                $image->resize(
                    $this->compressionOptions['max_width'],
                    $this->compressionOptions['max_height'],
                    function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    }
                );

                Log::info("Image redimensionnée : {$image->width()}x{$image->height()}");
            }

            // Compresser selon le format
            $extension = strtolower(pathinfo($this->filePath, PATHINFO_EXTENSION));
            $quality = $this->compressionOptions['quality'];

            if (in_array($extension, ['jpg', 'jpeg'])) {
                $image->encode('jpg', $quality);
            } elseif ($extension === 'png') {
                $image->encode('png', min(9, max(0, 10 - ($quality / 10))));
            }

            $compressedPath = tempnam(sys_get_temp_dir(), 'compressed_') . '.' . $extension;
            $image->save($compressedPath, $quality);

            Storage::put($this->filePath, file_get_contents($compressedPath));

            $originalSize = strlen($fileContent);
            $compressedSize = filesize($compressedPath);
            $reduction = round((1 - $compressedSize / $originalSize) * 100, 1);

            unlink($tempPath);
            unlink($compressedPath);

            Log::info("Compression terminée : {$this->filePath} - Réduction : {$reduction}%");

        } catch (\Exception $e) {
            Log::error("Erreur lors de la compression d'image : {$this->filePath} - " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Échec du job de compression d'image : {$this->filePath} - " . $exception->getMessage());
    }
}
