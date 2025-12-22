<?php

use App\Models\Laboratorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Commande\chatCommandeController;
use App\Http\Controllers\Api\Commande\CommandeController;
use App\Http\Controllers\Api\FraisController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\RechercheController;
use App\Http\Controllers\Api\User\RoleController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\LaboratorieController;
use App\Http\Controllers\Api\User\AgendaController;
use App\Http\Controllers\Api\Examen\ExamenController;
use App\Http\Controllers\Api\User\ResultatController;
use App\Http\Controllers\Api\Examen\TypeBilanController;
use App\Http\Controllers\NewsletterSubscriberController;
use App\Http\Controllers\Api\Wallet\WalletController;
use App\Http\Controllers\Api\Paiement\PaiementController;
use App\Http\Controllers\Api\QrCodeVerificationController;
use App\Http\Controllers\Api\PushSubscriptionController;
use App\Http\Controllers\Api\VilleController;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::post('login', [AuthController::class, 'login']);
// Route::post('register', [AuthController::class, 'register']);
Route::get('/run-commands', [CommandController::class, 'runCommands']);

Route::controller(chatCommandeController::class)->group(function () {
    Route::get('chat/send', 'sendMessagetest');
});
Route::controller(CommandeController::class)->group(function () {
    Route::get('commande/callback/', 'callback');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('password/forgot', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('password/resendotp', [ForgotPasswordController::class, 'reSendOtp']);
Route::get('/villes', [VilleController::class, 'index']);


// Public routes for practitioners
Route::get('practitioners/approved', [\App\Http\Controllers\Api\PractitionerApiController::class, 'getApprovedPractitioners']);
Route::get('practitioners/list', [\App\Http\Controllers\Api\PractitionerApiController::class, 'listPractitioners']);
Route::get('practitioner/view/{id}', [\App\Http\Controllers\Api\PractitionerApiController::class, 'viewProfile']);

Route::post('password/reset', [ResetPasswordController::class, 'resetPassword']);
Route::post('password/verifyotp', [ResetPasswordController::class, 'otpVerify']);

Route::post('email/verify', [EmailVerificationController::class, 'verify']);
Route::post('email/resend', [EmailVerificationController::class, 'resend']);
Route::post('email/status', [EmailVerificationController::class, 'status']);

Route::controller(NewsletterSubscriberController::class)->group(function () {
    Route::post('user/subscribe/newsletter/email', 'subscribeEmail');
    Route::post('user/unsubscribe/newsletter', 'unsubscribe');
});

Route::controller(ExamenController::class)->group(function () {
    Route::get('examen/list', 'listExamen');
    Route::get('examen/get', 'get');
});

Route::controller(LaboratorieController::class)->group(function () {
    Route::get('laboratorie/list', 'listLaboratorie');
    Route::get('laboratorie/get', 'get');
    Route::get('laboratorie/examens', 'getExamens');
    Route::get('laboratorie/bilans', 'getBilans');
});


Route::controller(TypeBilanController::class)->group(function () {
    Route::get('typebilan/list', 'listTypeBilan');
    Route::get('typebilan/get', 'get');
});

// Route::controller(RechercheController::class)->group(function () {
//     Route::post('search/list', 'searchExamenBilan');
// });

// Routes de recherche et filtrage
Route::get('/search', [RechercheController::class, 'searchExamenBilan']);
Route::get('/filters/options', [RechercheController::class, 'getFilterOptions']);
Route::get('/filters/examens', [RechercheController::class, 'getDistinctExamens']);

Route::controller(FraisController::class)->group(function () {
    Route::get('frais', 'getFrais'); // L'ancienne (juste le prix de base)

    // LA NOUVELLE ROUTE POUR LE CALCUL DYNAMIQUE
    Route::post('frais/calculate', 'calculateFrais');
});

Route::controller(UserController::class)->group(function () {
    Route::get('user/token', 'info_user');
    Route::post('support/message', 'createEmailSupport');
});

Route::controller(BlogApiController::class)->group(function () {
    Route::get('blog/list', 'index');   // Pour avoir tous les articles
    Route::get('blog/show/{id}', 'show'); // Pour avoir un seul article via son ID
    Route::post('blog/upload-image', 'uploadImage'); // Upload image for editor
    Route::post('blog/{id}/images', 'addImages'); // Upload multiple images for a blog
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::controller(ExamenController::class)->group(function () {
        Route::post('examen/create', 'create');
        Route::post('examen/update', 'update');
        Route::post('examen/delete', 'delete');
    });

    Route::controller(LaboratorieController::class)->group(function () {
        Route::post('laboratorie/create', 'create');
        Route::post('laboratorie/update', 'update');
        Route::post('laboratorie/delete', 'delete');
    });

    Route::controller(TypeBilanController::class)->group(function () {
        Route::post('typebilan/create', 'create');
        Route::post('typebilan/update', 'update');
        Route::post('typebilan/delete', 'delete');
    });

    Route::controller(AgendaController::class)->group(function () {
        Route::post('agenda/create', 'createAgenda');
        Route::post('agenda/update', 'updateAgenda');
        Route::post('agenda/delete', 'deleteAgenda');
        Route::get('agenda/list', 'listAgenda');
        Route::get('agenda/get', 'get');
    });

    Route::controller(CommandeController::class)->group(function () {
        Route::post('commande/create', 'create');
        Route::post('commande/update', 'update');
        Route::post('commande/delete', 'delete');
        Route::get('commande/list', 'listCommande');
        Route::get('commande/list/pending', 'listCommandePending');
        Route::get('commande/list/progress', 'listCommandeProgress');
        Route::get('commande/list/finish', 'listCommandeFinish');
        Route::get('commande/agent/list/pending', 'listCommandePendingAgent');
        Route::get('commande/agent/list/progress', 'listCommandeProgressAgent');
        Route::get('commande/agent/list/finish', 'listCommandeFinishAgent');
        Route::get('commande/get', 'get');
        Route::get('commande/code', 'get_CommandeByCode');
        Route::get('commande/update/statut', 'changeStatut');
        Route::get('commande/admin/list', 'listCommandeAdmin');
        Route::get('commande/agent/list', 'listCommmandeAgent');
        Route::get('commande/assign/agent', 'AssignAgentCommande');
    });

    Route::controller(PaiementController::class)->group(function () {
        Route::post('paiement/create', 'create');
        Route::post('paiement/manuel/create', 'createPaiementManuel');
        Route::post('paiement/update', 'update');
        Route::post('paiement/delete', 'delete');
        Route::get('paiement/list', 'listPaiement');
        Route::get('paiement/get', 'get');
        Route::get('paiement/code/get', 'get_Paiement_code');
        Route::get('paiement/update/statut', 'changeStatut');
        Route::get('paiement/admin/list', 'listPaiementAllAdmin');
    });

    Route::controller(ResultatController::class)->group(function () {
        Route::post('resultat/create', 'create');
        Route::post('resultat/update', 'update');
        Route::post('resultat/delete', 'delete');
        Route::get('resultat/list', 'listResultat');
        Route::get('resultat/get', 'get');
        Route::get('resultat/admin/list', 'listResultatAdmin');
    });

    Route::controller(chatCommandeController::class)->group(function () {
        Route::post('chat/commande/list', 'listChatCommandeConversations');
        Route::post('chat/commande/send', 'sendMessage');
        Route::post('chat/commande/message', 'getChatCommandeFor');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('user/notification', 'notify_user');
        Route::post('user/notification/markasread', 'markAsRead');
        Route::post('user/firebase/token', 'refresh_token_notify');
        Route::post('user/update', 'update_info');
        Route::post('user/update/profile', 'set_profile');
        Route::post('user/modifie/password', 'modifyPassword');
    });

    Route::controller(NewsletterSubscriberController::class)->group(function () {
        Route::post('user/subscribe/newsletter', 'subscribeUser');
    });

    // Practitioner Routes
    Route::prefix('practitioner')->controller(\App\Http\Controllers\Api\PractitionerApiController::class)->group(function () {
        Route::get('/profile', 'getProfile');
        Route::post('/profile/update', 'updateProfile');
        Route::get('/statistics', 'getStatistics');
    });

    // Practitioner Chat Routes
    Route::prefix('practitioner-chat')->controller(\App\Http\Controllers\Api\PractitionerChatController::class)->group(function () {
        Route::get('/conversations', 'getConversations');
        Route::post('/messages', 'getMessages');
        Route::post('/send', 'sendMessage');
        Route::post('/mark-as-read', 'markAsRead');
        Route::get('/unread-count', 'getUnreadCount');
    });

    // Unified Messages Routes (commandes + practitioners)
    Route::prefix('messages')->controller(\App\Http\Controllers\Api\UnifiedMessagesController::class)->group(function () {
        Route::get('/unread-count', 'getUnreadCount');
    });

    // Practitioner Chat Routes
    Route::prefix('practitioner/chat')->controller(\App\Http\Controllers\Api\PractitionerChatController::class)->group(function () {
        Route::get('/conversations', 'getConversations');
        Route::post('/messages', 'getMessages');
        Route::post('/send', 'sendMessage');
        Route::get('/info/{practitionerId}', 'getConversationInfo');
        Route::post('/mark-read', 'markAsRead');
    });
    // Wallet Routes (pour les laboratoires)
    Route::prefix('wallet')->controller(WalletController::class)->group(function () {
        Route::get('balance', 'balance');
        Route::get('stats', 'stats');
        Route::get('transactions', 'transactions');
        Route::get('monthly', 'monthlyBalances');
    });


    // QR Code Verification Routes
    Route::prefix('qr-verification')->group(function () {
        // Spécialiste récupérer le QR Code d'une commande
        Route::get('/commande/{commandeId}', [QrCodeVerificationController::class, 'getQrCode']);

        // Spécialiste régénérer le QR Code manuellement
        Route::post('/regenerate/{commandeId}', [QrCodeVerificationController::class, 'regenerateQrCode']);

        // Client vérifier le QR Code scanné
        Route::get('/verify/{token}', [QrCodeVerificationController::class, 'verifyQrCode']);

        // Obtenir le statut de vérification
        Route::get('/status/{commandeId}', [QrCodeVerificationController::class, 'getVerificationStatus']);
    });

    Route::post('/push/subscribe', [PushSubscriptionController::class, 'update']);
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'delete']);
});
