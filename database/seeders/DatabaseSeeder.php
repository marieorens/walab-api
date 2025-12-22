<?php

namespace Database\Seeders;

use App\Enum\StatutCommandeEnum;
use App\Models\Commande;
use App\Models\Examen;
use App\Models\Laboratorie;
use App\Models\Resultat;
use App\Models\Role;
use App\Models\TypeBilan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer les rôles une seule fois avec firstOrCreate
        $roleAdmin = Role::firstOrCreate(
            ['label' => 'admin'],
            ['value' => 'Admin']
        );
        
        $roleAgent = Role::firstOrCreate(
            ['label' => 'agent'],
            ['value' => 'Agent']
        );
        
        $roleClient = Role::firstOrCreate(
            ['label' => 'client'],
            ['value' => 'Client']
        );

        $roleAdminSup = Role::firstOrCreate(
            ['label' => 'admin Sup'],
            ['value' => 'Admin Sup']
        );

        $roleLaboratoire = Role::firstOrCreate(
            ['label' => 'laboratoire'],
            ['value' => 'Laboratoire']
        );

        $rolePractitioner = Role::firstOrCreate(
            ['label' => 'practitioner'],
            ['value' => 'Practitioner']
        );

        // Les utilisateurs peuvent être créés à chaque fois 
        // IMPORTANT: email_verified_at est défini pour permettre la connexion immédiate des utilisateurs de test
        $adminsup = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'Admin Sup',
            'email' => 'test@adminsup.com',
            'role_id' => $roleAdminSup->id,
            'token_notify' => 'jjfjdnvnk',
            'email_verified_at' => now(), //  Email vérifié pour les tests
        ]);

        $admin = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'Admin',
            'email' => 'test@admin.com',
            'role_id' => $roleAdmin->id,
            'token_notify' => 'jjfjdnvnk',
            'email_verified_at' => now(), //  Email vérifié pour les tests
        ]);

        $agent = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'agent',
            'email' => 'test@agent.com',
            'role_id' => $roleAgent->id,
            'token_notify' => 'jjfjdnvnk',
            'email_verified_at' => now(), //  Email vérifié pour les tests
        ]);

        $client = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'client',
            'email' => 'test@client.com',
            'role_id' => $roleClient->id,
            'token_notify' => 'jjfjdnvnk',
            'email_verified_at' => now(), //  Email vérifié pour les tests
        ]);

        // Créer un utilisateur laboratoire
        $laboUser = User::factory()->create([
            'firstname' => 'Laboratoire',
            'lastname' => 'Test',
            'email' => 'labo@test.com',
            'role_id' => $roleLaboratoire->id,
            'status' => 'active',
            'token_notify' => 'jjfjdnvnk',
            'email_verified_at' => now(), //  Email vérifié pour les tests
        ]);

        // Créer le laboratoire lié à cet utilisateur
        $labo = Laboratorie::create([
            'name' => "test laboratoire",
            'address' => "VON 123",
            'description' => "Laboratoire test",
            'image' => "/examen/examen.jpg",
            'user_id' => $laboUser->id, // Lier au user laboratoire
        ]);

        // Créer un deuxième utilisateur laboratoire pour les tests
        $laboUser2 = User::factory()->create([
            'firstname' => 'Test',
            'lastname' => 'Laboratoire',
            'email' => 'test@laboratoire.com',
            'role_id' => $roleLaboratoire->id,
            'status' => 'active',
            'phone' => '0123456789',
            'token_notify' => 'jjfjdnvnk',
            'email_verified_at' => now(), //  Email vérifié pour les tests
        ]);

        // Créer le laboratoire lié à ce deuxième utilisateur
        $labo2 = Laboratorie::create([
            'name' => "Laboratoire de Test",
            'address' => "123 Rue de Test, Ville Test",
            'description' => "Un laboratoire de test pour les fonctionnalités.",
            'image' => "/examen/examen.jpg",
            'user_id' => $laboUser2->id,
        ]);

        $examen = Examen::create([
            'label' => "test examen",
            'laboratorie_id' => $labo->id,
            'icon' => "/examen/examen.jpg",
            'price' => 5000,
            'description' => "test examen",
        ]);

        $bilan = TypeBilan::create([
            'label' => "test bilan",
            'laboratorie_id' => $labo->id,
            'icon' => "/typebilan/bilan.jpg",
            'price' => 5000,
            'description' => "test bilan",
        ]);
// En Attente
        Commande::create([
            'code' => "ERTYUIOBVFGH",
            'type' => "Examen",
            'adress' => "VON 338",
            'statut' => StatutCommandeEnum::PENDING,
            'date_prelevement' => '20 Avril 2024, 18h 30min',
            'examen_id' => $examen->id,
            'client_id' => $client->id,
        ]);

// En cours
        $commande = Commande::create([
            'code' => "ERTYUIOBVFG",
            'type' => "Examen",
            'adress' => "VON 338",
            'statut' => StatutCommandeEnum::PENDING,
            'date_prelevement' => '20 Avril 2024, 18h 30min',
            'type_bilan_id' => $bilan->id,
            'client_id' => $client->id,
        ]);

        $commande->update([
            'agent_id' => $agent->id,
            'statut' => StatutCommandeEnum::IN_PROGRESS,
        ]);

// Terminer
        $commande = Commande::create([
            'code' => "ERTYUIOBVTGH",
            'type' => "Examen",
            'adress' => "VON 338",
            'statut' => StatutCommandeEnum::PENDING,
            'date_prelevement' => '20 Avril 2024, 18h 30min',
            'examen_id' => $examen->id,
            'client_id' => $client->id,
        ]);

        $commande->update([
            'agent_id' => $agent->id,
            'statut' => StatutCommandeEnum::FINISH,
        ]);
        Resultat::create([
            'pdf_url' => "/resultat/resultat.pdf",
            'code_commande'  => $commande->code,
            'pdf_password'  => '123456789',
        ]);

    }

}
