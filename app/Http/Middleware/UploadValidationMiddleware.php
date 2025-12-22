<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier tous les fichiers uploadés dans la requête
        $files = $request->allFiles();

        foreach ($files as $fieldName => $file) {
            if (is_array($file)) {
                // Plusieurs fichiers pour le même champ
                foreach ($file as $singleFile) {
                    $this->validateUploadedFile($singleFile, $fieldName);
                }
            } else {
                // Un seul fichier
                $this->validateUploadedFile($file, $fieldName);
            }
        }

        return $next($request);
    }

    /**
     * Valide un fichier uploadé
     */
    protected function validateUploadedFile($file, string $fieldName): void
    {
        if (!$file instanceof \Illuminate\Http\UploadedFile) {
            return;
        }

        $config = config('uploads');
        $fileSize = $file->getSize();
        $extension = strtolower($file->getClientOriginalExtension());

        // Déterminer le type de fichier selon le nom du champ
        $fileType = $this->determineFileType($fieldName);

        // Vérifier la taille maximale
        $maxSize = $config['restrictions'][$fileType]['max_size'] ?? $config['restrictions']['documents']['max_size'];
        if ($fileSize > $maxSize) {
            $maxSizeMB = number_format($maxSize / (1024 * 1024), 1);
            abort(422, "Le fichier '{$fieldName}' est trop volumineux. Taille maximum : {$maxSizeMB}MB");
        }

        // Vérifier le type de fichier
        $allowedTypes = $config['restrictions'][$fileType]['allowed_types'] ?? $config['restrictions']['documents']['allowed_types'];
        if (!in_array($extension, $allowedTypes)) {
            $allowedTypesStr = implode(', ', $allowedTypes);
            abort(422, "Type de fichier non autorisé pour '{$fieldName}'. Types acceptés : {$allowedTypesStr}");
        }

        // Vérifier le type MIME
        $mimeType = $file->getMimeType();
        $allowedMimeTypes = $config['security']['allowed_mime_types'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            abort(422, "Type MIME non autorisé pour '{$fieldName}' : {$mimeType}");
        }

        // Vérification supplémentaire pour les images
        if (str_starts_with($mimeType, 'image/')) {
            // Vérifier que c'est bien une image valide
            try {
                $imageInfo = getimagesize($file->getPathname());
                if (!$imageInfo) {
                    abort(422, "Le fichier '{$fieldName}' n'est pas une image valide");
                }
            } catch (\Exception $e) {
                abort(422, "Erreur lors de la validation de l'image '{$fieldName}'");
            }
        }
    }

    /**
     * Détermine le type de fichier selon le nom du champ
     */
    protected function determineFileType(string $fieldName): string
    {
        $fieldMappings = [
            'image' => 'images',
            'photo' => 'images',
            'profile' => 'images',
            'avatar' => 'images',
            'logo' => 'images',
            'certificate' => 'certificates',
            'certificat' => 'certificates',
            'result' => 'results',
            'resultat' => 'results',
            'document' => 'documents',
            'file' => 'documents',
        ];

        foreach ($fieldMappings as $keyword => $type) {
            if (str_contains(strtolower($fieldName), $keyword)) {
                return $type;
            }
        }

        return 'documents'; // Type par défaut
    }
}
