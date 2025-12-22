<?php

use App\Http\Controllers\NewsletterSubscriberController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AgentController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BilanController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\CommandeChatController;
use App\Http\Controllers\Web\CommandeController;
use App\Http\Controllers\Web\ExamenController;
use App\Http\Controllers\Web\ExportController;
use App\Http\Controllers\Web\HomeWebContoller;
use App\Http\Controllers\Web\LaboratorieController;
use App\Http\Controllers\Web\PractitionerController;
use App\Http\Controllers\Web\ResultatController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\Laboratoire\AuthController as LaboratoireAuthController;
use App\Http\Controllers\Web\Laboratoire\DashboardController as LaboratoireDashboardController;
use App\Http\Controllers\Web\Laboratoire\RegisterController as LaboratoireRegisterController;
use App\Http\Controllers\Web\Laboratoire\ProfileController as LaboratoireProfileController;
use App\Http\Controllers\Web\Laboratoire\WalletController as LaboratoireWalletController;
use App\Http\Controllers\Web\PaiementController;
use App\Http\Controllers\Web\WalletController;
use App\Http\Controllers\Web\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Web\VilleController;



//  Route::get('/', function () {
//     return view('welcome');
//  });


Route::get('/login', [AuthController::class, 'create_login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login', [AuthController::class, 'store_login'])->name('login.store');


Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::post('/store', [BlogController::class, 'store'])->name('store');
    Route::post('/update/{id}', [BlogController::class, 'update'])->name('update');
    Route::get('/delete/{id}', [BlogController::class, 'destroy'])->name('destroy');
});

Route::middleware(['web', 'auth'])->group(function () {

    Route::get('/dashboard',  [HomeWebContoller::class, 'index'])->name('home');

    Route::resource('/admins',  AdminController::class);
    Route::resource('/agents',  AgentController::class);
    Route::resource('/users',  UserController::class);
    Route::resource('/clients',  ClientController::class);
    Route::resource('/roles',  RoleController::class);
    Route::resource('/examens',  ExamenController::class);
    Route::resource('/laboratories',  LaboratorieController::class);
    Route::get('/laboratorie/examen/{id}',  [ExamenController::class, 'lab_examen']);
    Route::get('/laboratorie/bilan/{id}',  [BilanController::class, 'lab_bilan']);
    Route::resource('/bilans',  BilanController::class);
    Route::resource('/commandes',  CommandeController::class);
    Route::get('/commande/details/{id}', [CommandeController::class, 'details'])->name('commande.details');
    Route::post('/commande/admin-upload-batch', [CommandeController::class, 'admin_upload_batch'])->name('commande.admin_upload_batch');
    Route::post('/commande/admin-terminer/{code}', [CommandeController::class, 'admin_terminer'])->name('commande.admin_terminer');
    Route::get('/commande/admin-delete-resultat/{id}', [CommandeController::class, 'admin_delete_resultat'])->name('commande.admin_delete_resultat');
    Route::resource('/resultats',  ResultatController::class);
    Route::resource('/commande/chats',  CommandeChatController::class);

    // Routes Paiements (Admin)
    Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
    Route::get('/paiements/{id}', [PaiementController::class, 'show'])->name('paiements.show');

    Route::get('/commandes/assigne/{id}', [CommandeController::class, 'assigne']);
    Route::get('/commandes/update/assigne', [CommandeController::class, 'assigne_update']);
    Route::get('/commande-data', [CommandeController::class, 'getData']);
    Route::get('/user/account/{user}', [UserController::class, 'account']);
    Route::get('/user/bloquer/{user}', [AdminController::class, 'bloquer']);
    Route::post('/client/update/{user}', [ClientController::class, 'update'])->name("client_update");
    Route::post('/agent/update/{user}', [AgentController::class, 'update'])->name("agent_update");
    Route::post('/admin/update/{user}', [AdminController::class, 'update'])->name("admin_update");


    Route::post('/bilan/update/{user}', [BilanController::class, 'update'])->name("bilan_update");
    Route::post('/examen/update/{user}', [ExamenController::class, 'update'])->name("examen_update");
    Route::post('/laboratoire/update/{user}', [LaboratorieController::class, 'update'])->name("laboratoire_update");
    Route::post('/user/password/{user}', [UserController::class, 'update_password'])->name('user_password');
    Route::get('/clients_destroy/{id}', [ClientController::class, 'destroy'])->name('clients_destroy');
    Route::get('/agents_destroy/{id}', [AgentController::class, 'destroy'])->name('agents_destroy');
    Route::get('/admins_destroy/{id}', [AdminController::class, 'destroy'])->name('admins_destroy');
    Route::get('/practitioners_destroy/{id}', [PractitionerController::class, 'destroy'])->name('practitioners_destroy');
    Route::get('/examen/destroy/{id}', [ExamenController::class, 'destroy'])->name('examen_destroy');
    Route::get('/examen/active/{id}', [ExamenController::class, 'active']);
    Route::get('/bilan/destroy/{id}', [BilanController::class, 'destroy'])->name('bilan_destroy');
    Route::get('/bilan/active/{id}', [BilanController::class, 'active']);
    Route::get('/resultat/destroy/{id}', [ExamenController::class, 'destroy'])->name('resultat_destroy');
    Route::get('/laboratoire/destroy/{id}', [LaboratorieController::class, 'destroy'])->name('laboratoire_destroy');
    Route::get('/laboratoire/valider/{id}', [LaboratorieController::class, 'valider'])->name('laboratoire_valider');
    Route::get('/laboratoire/suspendre/{id}', [LaboratorieController::class, 'suspendre'])->name('laboratoire_suspendre');
    Route::get('/laboratoire/activer/{id}', [LaboratorieController::class, 'activer'])->name('laboratoire_activer');
    Route::get('/commandes/create/{user}',  [CommandeController::class, "create_user"])->name('commandes_create');
    Route::get('/resultat/commande/{code}',  [ResultatController::class, "resCommande"]);
    Route::post('/resultat/commande/create/{code}',  [ResultatController::class, "createResultat"]);
    Route::get('/search',  [SearchController::class, "search"]);

    Route::get('exporter/agent', [ExportController::class, 'exporter_agent'])->name('exporter.agent');
    Route::get('exporter/client', [ExportController::class, 'exporter_client'])->name('exporter.client');
    Route::get('exporter/practitioner', [ExportController::class, 'exporter_practitioner'])->name('exporter.practitioner');
    Route::get('exporter/laboratoire', [ExportController::class, 'exporter_laboratoire'])->name('exporter.laboratoire');
    Route::get('exporter/admin', [ExportController::class, 'exporter_admin'])->name('exporter.admin');
    Route::get('exporter/examen', [ExportController::class, 'exporter_examen'])->name('exporter.examen');
    Route::get('exporter/bilan', [ExportController::class, 'exporter_bilan'])->name('exporter.bilan');
    Route::get('exporter/commande', [ExportController::class, 'exporter_commande'])->name('exporter.commande');

    Route::get('/newletters_destroy/{id}', [NewsletterSubscriberController::class, 'destroy'])->name('newletter_destroy');
    Route::get('/newsletter', [NewsletterSubscriberController::class, 'index'])->name('newletter.index');
    Route::get('/newsletter/subscribe', [NewsletterSubscriberController::class, 'indexSubscribe'])->name('newslettersubscriber.index');
    Route::post('/newsletter/create', [NewsletterSubscriberController::class, 'store'])->name("newletters.store");
    
    // Routes Notifications (Labo, Admin, tous utilisateurs web)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/frais', [HomeWebContoller::class, 'storeFrais'])->name("frais");

    // ROUTES PRACTITIONERS
    Route::prefix('practitioners')->name('practitioner.')->group(function () {
        Route::get('/', [PractitionerController::class, 'index'])->name('index');
        Route::get('/create', [PractitionerController::class, 'create'])->name('create');
        Route::post('/store', [PractitionerController::class, 'store'])->name('store');
        Route::get('/show/{id}', [PractitionerController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [PractitionerController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PractitionerController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [PractitionerController::class, 'destroy'])->name('destroy');
        Route::post('/approve/{id}', [PractitionerController::class, 'approve'])->name('approve');
        Route::post('/reject/{id}', [PractitionerController::class, 'reject'])->name('reject');
        Route::post('/toggle-status/{id}', [PractitionerController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Routes Portefeuilles (Admin uniquement)
    Route::prefix('wallets')->name('wallets.')->middleware('admin')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/platform', [WalletController::class, 'platformWallet'])->name('platform');
        Route::get('/withdrawals', [WalletController::class, 'withdrawals'])->name('withdrawals');
        Route::post('/withdrawals/generate', [WalletController::class, 'generateWithdrawals'])->name('withdrawals.generate');
        Route::post('/withdrawals/{id}/process', [WalletController::class, 'processWithdrawal'])->name('withdrawals.process');
        Route::get('/{id}', [WalletController::class, 'show'])->name('show');
        Route::get('/{id}/transactions', [WalletController::class, 'transactions'])->name('transactions');
        Route::post('/{id}/adjust', [WalletController::class, 'adjust'])->name('adjust');
        Route::post('/{id}/block', [WalletController::class, 'block'])->name('block');
        Route::post('/{id}/suspend', [WalletController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/activate', [WalletController::class, 'activate'])->name('activate');
    });

    Route::get('/villes', [VilleController::class, 'index'])->name('villes.index');
    Route::post('/villes', [VilleController::class, 'store'])->name('villes.store');
    Route::put('/villes/{id}', [VilleController::class, 'update'])->name('villes.update');
    Route::delete('/villes/{id}', [VilleController::class, 'destroy'])->name('villes.destroy');
    Route::get('/villes/toggle/{id}', [VilleController::class, 'toggle'])->name('villes.toggle');

});

// ROUTES LABO
Route::prefix('laboratoire')->name('laboratoire.')->group(function () {
    Route::get('/register', [LaboratoireRegisterController::class, 'create'])->name('register');
    Route::post('/register', [LaboratoireRegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LaboratoireAuthController::class, 'create_login'])->name('login');
    Route::post('/login', [LaboratoireAuthController::class, 'store_login'])->name('login.store');
    Route::get('/logout', [LaboratoireAuthController::class, 'logout'])->name('logout');


    Route::middleware(['web', 'auth', \App\Http\Middleware\CheckLaboratoireSuspended::class])->group(function () {
        Route::get('/dashboard', [LaboratoireDashboardController::class, 'index'])->name('dashboard');

        Route::get('/examens', [LaboratoireDashboardController::class, 'examens'])->name('examens');
        Route::post('/examen/ajouter', [LaboratoireDashboardController::class, 'store_examen'])->name('examen.store');
        Route::post('/examen/modifier/{id}', [LaboratoireDashboardController::class, 'update_examen'])->name('examen.update');
        Route::get('/examen/supprimer/{id}', [LaboratoireDashboardController::class, 'delete_examen'])->name('examen.delete');
        Route::get('/examen/activer/{id}', [LaboratoireDashboardController::class, 'toggle_examen'])->defaults('action', 'activer');
        Route::get('/examen/desactiver/{id}', [LaboratoireDashboardController::class, 'toggle_examen'])->defaults('action', 'desactiver');

        Route::get('/bilans', [LaboratoireDashboardController::class, 'bilans'])->name('bilans');
        Route::post('/bilan/ajouter', [LaboratoireDashboardController::class, 'store_bilan'])->name('bilan.store');
        Route::post('/bilan/modifier/{id}', [LaboratoireDashboardController::class, 'update_bilan'])->name('bilan.update');
        Route::get('/bilan/supprimer/{id}', [LaboratoireDashboardController::class, 'delete_bilan'])->name('bilan.delete');
        Route::get('/bilan/activer/{id}', [LaboratoireDashboardController::class, 'toggle_bilan'])->defaults('action', 'activer');
        Route::get('/bilan/desactiver/{id}', [LaboratoireDashboardController::class, 'toggle_bilan'])->defaults('action', 'desactiver');

        Route::get('/commandes', [LaboratoireDashboardController::class, 'commandes'])->name('commandes');
        Route::get('/commande/details/{id}', [LaboratoireDashboardController::class, 'commande_details'])->name('commande.details');
        Route::post('/commande/upload-resultat/{id}', [LaboratoireDashboardController::class, 'upload_resultat'])->name('commande.upload_resultat');
        Route::post('/commande/upload-batch', [LaboratoireDashboardController::class, 'upload_batch_resultat'])->name('commande.upload_batch');
        Route::post('/commande/terminer/{code}', [LaboratoireDashboardController::class, 'terminer_commande'])->name('commande.terminer');
        Route::get('/commande/delete-resultat/{id}', [LaboratoireDashboardController::class, 'delete_resultat'])->name('commande.delete_resultat');

        // Routes Portefeuille Laboratoire
        Route::get('/wallet', [LaboratoireWalletController::class, 'index'])->name('wallet');
        Route::get('/wallet/transactions', [LaboratoireWalletController::class, 'transactions'])->name('wallet.transactions');
        Route::get('/wallet/withdrawals', [LaboratoireWalletController::class, 'withdrawals'])->name('wallet.withdrawals');

        // Routes Profil Laboratoire
        Route::get('/profile', [LaboratoireProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [LaboratoireProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [LaboratoireProfileController::class, 'update'])->name('profile.update');

    });
});
