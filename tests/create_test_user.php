<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'test@laboratoire.com')->first();
if (!$user) {
    $user = \App\Models\User::create([
        'firstname' => 'Test',
        'lastname' => 'Laboratoire', 
        'email' => 'test@laboratoire.com',
        'password' => bcrypt('password'),
        'role_id' => 4,
        'phone' => '0123456789',
        'email_verified_at' => now(), // Email vérifié pour éviter la vérification OTP
    ]);
    
    \App\Models\Laboratorie::create([
        'user_id' => $user->id,
        'name' => 'Laboratoire de Test',
        'address' => '123 Rue de Test, Ville Test',
        'description' => 'Un laboratoire de test pour les fonctionnalités.'
    ]);
    
    echo 'Utilisateur et laboratoire de test créés.' . PHP_EOL;
    echo 'Email: test@laboratoire.com' . PHP_EOL;
    echo 'Mot de passe: password' . PHP_EOL;
} else {
    echo 'L\'utilisateur de test existe déjà.' . PHP_EOL;
}
