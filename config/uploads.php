<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration des Uploads
    |--------------------------------------------------------------------------
    |
    | Configuration centralisée pour la gestion des fichiers uploadés
    | Restrictions de taille, types autorisés, compression, etc.
    |
    */

    'restrictions' => [
        'images' => [
            'max_size' => 5 * 1024 * 1024, // 5MB
            'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'quality' => 85, // Qualité de compression JPEG/WebP
        ],

        'documents' => [
            'max_size' => 10 * 1024 * 1024, // 10MB
            'allowed_types' => ['pdf', 'doc', 'docx', 'txt'],
        ],

        'certificates' => [
            'max_size' => 2 * 1024 * 1024, // 2MB
            'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
        ],

        'results' => [
            'max_size' => 15 * 1024 * 1024, // 15MB (PDFs médicaux peuvent être volumineux)
            'allowed_types' => ['pdf'],
        ],
    ],

    'compression' => [
        'enabled' => true,
        'image_quality' => 85,
        'max_width' => 1920, // Redimensionnement max pour les grandes images
        'max_height' => 1080,
        'convert_to_webp' => false, // Convertir automatiquement en WebP
    ],

    'storage' => [
        'disk' => env('FILESYSTEM_DISK', 'public'),
        'directories' => [
            'profiles' => 'profiles',
            'laboratories' => 'laboratories',
            'exams' => 'exams',
            'results' => 'results',
            'certificates' => 'certificates',
            'blogs' => 'blogs',
            'temp' => 'temp',
        ],
    ],

    'security' => [
        'scan_virus' => false, // À activer si ClamAV installé
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain',
        ],
    ],
];