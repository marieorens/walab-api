# 📬 Système de Notifications et Emails - Documentation Complète

## Vue d'ensemble

Le système WaLab dispose d'un système complet de notifications multi-canaux :
- **Notifications internes** (base de données) : visibles dans le dashboard avec badge de compteur
- **Notifications push** (Web Push API) : alertes navigateur en temps réel
- **Emails** : notifications par email pour les événements importants

---

## 🔔 Interface Utilisateur - Dashboards Web (Labo & Admin)

### 🎯 Cloche de Notification

**Emplacement** : Barre de navigation supérieure de chaque dashboard

**Fonctionnalités** :
- ✅ Icône de cloche visible en permanence
- ✅ Badge rouge avec compteur de notifications non lues (1, 2, 3...99+)
- ✅ Le badge se met à jour automatiquement toutes les 30 secondes
- ✅ Clic sur la cloche → Redirection vers la page des notifications
- ✅ Le compteur se décrémente automatiquement quand les notifications sont lues

**Accès direct** :
- **Admin** : `https://votre-domaine.com/notifications`
- **Laboratoire** : `https://votre-domaine.com/notifications`

### 📋 Page de Notifications

**Fonctionnalités complètes** :

1. **Filtres** :
   - 🔹 **Toutes** : Affiche toutes les notifications
   - 🔹 **Non lues** : Uniquement les notifications non consultées (avec compteur)
   - 🔹 **Lues** : Historique des notifications déjà consultées

2. **Affichage des notifications** :
   - 📌 Titre de la notification en gras
   - 📌 Badge bleu pour les notifications non lues
   - 📌 Temps relatif (il y a 5 min, il y a 2 heures, etc.)
   - 📌 État replié par défaut (économie d'espace)

3. **Actions** :
   - **Cliquer sur une notification** : Déplier/replier le contenu complet
   - **Déplier une notification non lue** : Marque automatiquement comme lue après 0.5s
   - **Bouton "Marquer comme lu"** : Marquer manuellement une notification
   - **Bouton "Tout marquer comme lu"** : Marquer toutes les notifications d'un coup
   - **Bouton "Voir"** : Si la notification contient un lien vers une ressource

4. **Design** :
   - ✨ Notifications non lues : Fond bleu clair + bordure gauche bleue
   - ✨ Notifications lues : Apparence atténuée (opacité réduite)
   - ✨ Effet hover sur chaque ligne
   - ✨ Animation d'expansion/réduction fluide

---

## 🔔 Notifications par Rôle

### 👨‍⚕️ **LABORATOIRES**

#### Lors d'une nouvelle commande
✅ **Notification interne** : "Nouvelle Commande - Commande #CODE passée par CLIENT"
✅ **Notification push** : "🔔 Nouvelle Commande - Commande #CODE - Type"
✅ **Email** : Email détaillé avec toutes les informations de la commande

**Contenu de l'email :**
- Code de commande
- Informations du client (nom, email, téléphone)
- Adresse de prélèvement
- Date de prélèvement souhaitée
- Liste des examens/bilans commandés avec prix
- Statut de paiement
- Lien direct vers la commande

#### Lors de l'upload d'un résultat
✅ **Notification interne** : confirmant l'upload du résultat

---

### 👨‍💼 **ADMINS (Admin & Admin Sup)**

#### Lors d'une nouvelle commande
✅ **Notification interne** : "Nouvelle Commande - Commande #CODE passée par CLIENT"
✅ **Visible dans dashboard** : Badge cloche mis à jour instantanément

#### Lors d'un upload de résultat par un laboratoire
✅ **Notification interne** : "Nouveau Résultat - LABO_NAME a uploadé un résultat pour la commande #CODE"
✅ **Visible dans dashboard** : Badge cloche mis à jour

#### Lors d'une inscription de laboratoire
✅ **Notification interne** : "Nouveau laboratoire inscrit"
✅ **Notification push** : Alerte d'inscription laboratoire

#### Retraits mensuels en attente
✅ **Notification interne** : "X retraits en attente pour la période Y"

---

### 🚴 **AGENTS**

#### Lors de l'assignation d'une commande
✅ **Notification interne** : "Une nouvelle commande vous a été assignée, code : CODE"
✅ **Notification push** : "Nouvelle commande assignée"

#### Lors de nouveaux messages dans le chat
✅ **Notification push** : "Nouveau message de CLIENT/ADMIN"

---

### 👤 **CLIENTS**

#### Lors de la création d'une commande
✅ **Notification interne** : "Votre commande : CODE est en attente de traitement"
✅ **Email** : Confirmation de commande

#### Lors de l'assignation d'un agent
✅ **Notification interne** : "Votre commande : CODE est en cours de traitement"
✅ **Notification push** : "Commande assignée à un agent"

#### Lors de la disponibilité des résultats
✅ **Notification interne** : "Vos résultats sont disponibles"
✅ **Notification push** : "📄 Résultats disponibles"
✅ **Email** : Email de notification avec lien vers les résultats

#### Lors de nouveaux messages dans le chat
✅ **Notification push** : "Nouveau message de AGENT/ADMIN"

---

## 🛠️ Implémentation Technique

### Backend (Laravel)

#### Routes Web (/notifications)
```php
Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('notifications.index'); // Vue Blade + API JSON

Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.markAsRead');

Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
    ->name('notifications.markAllAsRead');

Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])
    ->name('notifications.unreadCount');
```

#### Contrôleur
`App\Http\Controllers\Web\NotificationController`

**Méthodes** :
- `index()` : Retourne la vue Blade OU JSON selon la requête
- `markAsRead()` : Marquer une notification comme lue
- `markAllAsRead()` : Marquer toutes les notifications comme lues
- `unreadCount()` : Récupérer le compteur de non lues (pour le badge)

#### Notifications disponibles
- `CommandeNotification` : Notifications génériques (commande, résultat, etc.)
- `SendPushNotification` : Notifications push navigateur
- `NewLabRegistrationNotification` : Inscription laboratoire
- `AdminValidationNotification` : Validation admin
- `WithdrawalsPendingNotification` : Retraits en attente

### Frontend (Blade Templates)

#### Layouts modifiés
1. **`resources/views/layout.blade.php`** (Admin)
   - Cloche avec badge dans la navbar
   - Script de mise à jour automatique du badge

2. **`resources/views/laboratoire/layout.blade.php`** (Labo)
   - Cloche avec badge dans la navbar
   - Script de mise à jour automatique du badge

#### Vue des notifications
**`resources/views/notifications/index.blade.php`**
- Interface complète de gestion des notifications
- Filtres (Toutes, Non lues, Lues)
- Système d'expansion/réduction
- Actions (marquer comme lu, voir, tout marquer)

#### JavaScript intégré
```javascript
// Mise à jour automatique du badge toutes les 30 secondes
setInterval(updateNotificationBadge, 30000);

// Au chargement de la page
document.addEventListener('DOMContentLoaded', updateNotificationBadge);
```

---

## 📧 Configuration Email

### Prérequis
Le fichier `.env` doit être configuré avec les informations SMTP :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

⚠️ **Important** : Pour Gmail, utilisez un mot de passe d'application, pas votre mot de passe principal.

### Emails disponibles

1. **NewCommandeMail** : Envoyé au laboratoire lors d'une nouvelle commande
2. **ResultatDisponibleMail** : Envoyé au client quand les résultats sont prêts
3. **WelcomeMail** : Email de bienvenue
4. **NewsletterMail** : Emails de newsletter
5. **ContactSupport** : Emails de support

---

## 🔔 Configuration Notifications Push

### Côté Backend

Le package `laravel-notification-channels/webpush` est installé et configuré.

**Configuration dans `config/webpush.php` :**
- Clés VAPID générées automatiquement
- Configuration des notifications push

### Côté Frontend

**Service Worker** : `/walab-web-app/public/sw.js`
**Utilitaires** : `/walab-web-app/src/utils/pushNotifications.js`

**Fonctions disponibles :**
- `checkPushSubscription()` : Vérifier si l'utilisateur est abonné
- `subscribeUserToPush()` : Abonner l'utilisateur aux notifications
- `sendTestNotification()` : Envoyer une notification de test

### Activation pour un utilisateur

```javascript
import { subscribeUserToPush } from './utils/pushNotifications';

// Abonner l'utilisateur lors de la connexion
await subscribeUserToPush();
```

### Tester les notifications push

```javascript
import { sendTestNotification } from './utils/pushNotifications';

// Envoyer une notification de test
await sendTestNotification();
```

---

## 🧪 Tests

### Tester les notifications laboratoire

1. Se connecter en tant que client
2. Passer une commande avec des examens/bilans
3. Vérifier :
   - ✅ Notification dans le dashboard laboratoire
   - ✅ Notification push (si activée)
   - ✅ Email reçu dans la boîte du laboratoire

### Tester les notifications client

1. Se connecter en tant que laboratoire
2. Uploader un résultat pour une commande
3. Vérifier :
   - ✅ Notification dans le dashboard client
   - ✅ Notification push (si activée)
   - ✅ Email de résultat reçu

### Tester les notifications agent

1. Se connecter en tant qu'admin/laboratoire
2. Assigner une commande à un agent
3. Vérifier :
   - ✅ Notification dans le dashboard agent
   - ✅ Notification push (si activée)

---

## 📊 Base de données

### Table `notifications`

Toutes les notifications internes sont stockées dans cette table :

```sql
SELECT * FROM notifications 
WHERE notifiable_id = USER_ID 
ORDER BY created_at DESC;
```

### Marquer une notification comme lue

```php
$notification = auth()->user()->notifications()->find($notificationId);
$notification->markAsRead();
```

### Récupérer les notifications non lues

```php
$unreadNotifications = auth()->user()->unreadNotifications;
```

---

## 🛠️ Dépannage

### Les emails ne sont pas envoyés

1. Vérifier la configuration SMTP dans `.env`
2. Vérifier que `MAIL_MAILER=smtp` (pas `log`)
3. Tester avec :
```bash
cd walab-api
php artisan tinker
Mail::raw('Test email', function($message) {
    $message->to('votre-email@test.com')->subject('Test');
});
```

### Les notifications push ne fonctionnent pas

1. Vérifier que le Service Worker est enregistré :
```javascript
navigator.serviceWorker.getRegistration().then(reg => console.log(reg));
```

2. Vérifier les permissions :
```javascript
console.log(Notification.permission); // doit être "granted"
```

3. Réabonner l'utilisateur :
```javascript
await subscribeUserToPush();
```

### Les notifications internes n'apparaissent pas

1. Vérifier que la notification est bien créée :
```sql
SELECT * FROM notifications WHERE notifiable_id = USER_ID ORDER BY created_at DESC LIMIT 5;
```

2. Vérifier que le frontend récupère les notifications :
```javascript
// Endpoint : GET /api/user/notifications
```

---

## 📝 Ajouter de nouvelles notifications

### 1. Créer une nouvelle classe Mailable (pour email)

```bash
php artisan make:mail VotreNouvelleMail
```

### 2. Créer une notification

```bash
php artisan make:notification VotreNotification
```

### 3. Utiliser la notification

```php
use App\Notifications\VotreNotification;

$user->notify(new VotreNotification($data));
```

### 4. Envoyer un email

```php
use App\Mail\VotreNouvelleMail;
use Illuminate\Support\Facades\Mail;

Mail::to($user->email)->send(new VotreNouvelleMail($data));
```

---

## ✅ Checklist de déploiement

Avant de déployer en production :

- [ ] Configuration SMTP vérifiée dans `.env`
- [ ] Clés VAPID générées pour les notifications push
- [ ] Service Worker accessible à `/sw.js`
- [ ] Tests d'envoi d'emails réussis
- [ ] Tests de notifications push réussis
- [ ] Permissions de notifications accordées dans le navigateur
- [ ] Variables d'environnement configurées sur le serveur

---

## 📞 Support

Pour toute question ou problème :
1. Vérifier les logs Laravel : `storage/logs/laravel.log`
2. Vérifier les logs du navigateur (Console DevTools)
3. Consulter cette documentation

---

**Dernière mise à jour** : 20 décembre 2025
