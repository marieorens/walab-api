<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UploadService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('uploads');
    }

    /**
     * Upload et traite un fichier image
     */
    public function uploadImage(UploadedFile $file, string $directory, string $filename = null): array
    {
        // Validation de base
        $this->validateFile($file, 'images');

        // Générer un nom de fichier unique si non fourni
        $filename = $filename ?: $this->generateUniqueFilename($file, 'image');

        // Traiter l'image (compression/redimensionnement)
        $processedFile = $this->processImage($file);

        // Sauvegarder le fichier
        $path = $processedFile->storeAs($directory, $filename, $this->config['storage']['disk']);

        return [
            'path' => $path,
            'url' => Storage::disk($this->config['storage']['disk'])->url($path),
            'filename' => $filename,
            'size' => $processedFile->getSize(),
            'mime_type' => $processedFile->getMimeType(),
        ];
    }

    public function uploadDocument(UploadedFile $file, string $directory, string $type = 'documents'): array
    {
        $this->validateFile($file, $type);

        $filename = $this->generateUniqueFilename($file, 'document');
        $path = $file->storeAs($directory, $filename, $this->config['storage']['disk']);

        return [
            'path' => $path,
            'url' => Storage::disk($this->config['storage']['disk'])->url($path),
            'filename' => $filename,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ];
    }

    /**
     * Upload un certificat médical
     */
    public function uploadCertificate(UploadedFile $file, string $directory = 'certificates'): array
    {
        return $this->uploadDocument($file, $directory, 'certificates');
    }

    public function uploadResult(UploadedFile $file, string $directory = 'results'): array
    {
        return $this->uploadDocument($file, $directory, 'results');
    }

    protected function validateFile(UploadedFile $file, string $type): void
    {
        $rules = $this->config['restrictions'][$type] ?? $this->config['restrictions']['documents'];

        // Vérifier la taille
        if ($file->getSize() > $rules['max_size']) {
            $maxSizeMB = $rules['max_size'] / (1024 * 1024);
            throw new \Exception("Le fichier est trop volumineux. Taille maximum autorisée : {$maxSizeMB}MB");
        }

        // Vérifier le type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $rules['allowed_types'])) {
            $allowedTypes = implode(', ', $rules['allowed_types']);
            throw new \Exception("Type de fichier non autorisé. Types acceptés : {$allowedTypes}");
        }

        // Vérifier le type MIME
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->config['security']['allowed_mime_types'])) {
            throw new \Exception("Type MIME non autorisé : {$mimeType}");
        }
    }

    protected function processImage(UploadedFile $file): UploadedFile
    {
        if (!$this->config['compression']['enabled']) {
            return $file;
        }

        // Créer un gestionnaire d'image Intervention
        $manager = new ImageManager(Driver::class);

        // Créer une image Intervention
        $image = $manager->read($file->get());

        // Redimensionner si trop grande
        $maxWidth = $this->config['compression']['max_width'];
        $maxHeight = $this->config['compression']['max_height'];

        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->scale(width: $maxWidth, height: $maxHeight);
        }

        // Compresser selon le type
        $quality = $this->config['compression']['image_quality'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['jpg', 'jpeg'])) {
            $encodedImage = $image->toJpeg($quality);
        } elseif ($extension === 'png') {
            $encodedImage = $image->toPng();
        } else {
            $encodedImage = $image->toJpeg($quality); // Default to JPEG
        }

        // Sauvegarder temporairement et retourner comme UploadedFile
        $tempPath = tempnam(sys_get_temp_dir(), 'compressed_') . '.' . $extension;
        file_put_contents($tempPath, $encodedImage);

        return new UploadedFile($tempPath, $file->getClientOriginalName(), $file->getMimeType(), null, true);
    }

    /**
     * Génère un nom de fichier unique
     */
    protected function generateUniqueFilename(UploadedFile $file, string $prefix = 'file'): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Ymd_His');
        $random = Str::random(8);

        return "{$prefix}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Supprime un fichier
     */
    public function deleteFile(string $path): bool
    {
        return Storage::disk($this->config['storage']['disk'])->delete($path);
    }

    /**
     * Vérifie si un fichier existe
     */
    public function fileExists(string $path): bool
    {
        return Storage::disk($this->config['storage']['disk'])->exists($path);
    }

    /**
     * Obtient l'URL d'un fichier
     */
    public function getFileUrl(string $path): string
    {
        return Storage::disk($this->config['storage']['disk'])->url($path);
    }
}