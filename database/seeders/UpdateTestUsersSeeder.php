<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UpdateTestUsersSeeder extends Seeder
{
    /**
     * Mettre à jour les utilisateurs de test pour vérifier automatiquement leur email
     */
    public function run(): void
    {
        // Liste des emails de test à mettre à jour
        $testEmails = [
            'test@adminsup.com',
            'test@admin.com',
            'test@agent.com',
            'test@client.com',
            'labo@test.com',
            'test@laboratoire.com',
        ];

        $updatedCount = 0;

        foreach ($testEmails as $email) {
            $user = User::where('email', $email)->first();
            
            if ($user) {
                // Vérifier si l'email n'est pas déjà vérifié
                if (!$user->email_verified_at) {
                    $user->email_verified_at = Carbon::now();
                    $user->save();
                    
                    $this->command->info("Email vérifié pour : {$email}");
                    $updatedCount++;
                } else {
                    $this->command->info("ℹEmail déjà vérifié : {$email}");
                }
            } else {
                $this->command->warn("Utilisateur non trouvé : {$email}");
            }
        }

        $this->command->info("Total mis à jour : {$updatedCount} utilisateur(s)");
    }
}
