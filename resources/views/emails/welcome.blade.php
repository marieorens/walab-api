<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur Walab</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
        }
        h1 {
            color: #4CAF50;
            font-size: 24px;
        }
        .welcome-message {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .features {
            margin: 20px 0;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px;
            background-color: #e8f5e9;
            border-radius: 5px;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 10px;
        }
        .cta-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">WALAB</div>
            <p>Vos analyses médicales simplifiées</p>
        </div>

        <h1>Bienvenue {{ $user->firstname ?? '' }} ! 🎉</h1>

        <div class="welcome-message">
            <p>Nous sommes ravis de vous compter parmi nous !</p>
            <p>Votre compte Walab a été créé avec succès. Vous pouvez maintenant accéder à tous nos services d'analyses médicales.</p>
        </div>

        <div class="features">
            <h3>Ce que vous pouvez faire avec Walab :</h3>
            <div class="feature-item">
                <span>Commander vos analyses médicales en ligne</span>
            </div>
            <div class="feature-item">
                <span>Prélèvement à domicile disponible</span>
            </div>
            <div class="feature-item">
                <span>Consulter vos résultats en toute sécurité</span>
            </div>
            <div class="feature-item">
                <span>Choisir parmi nos laboratoires partenaires</span>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ config('app.frontend_url', 'https://walab.bj') }}" class="cta-button">
                Accéder à mon compte
            </a>
        </div>

        <p><strong>Vos informations de compte :</strong></p>
        <ul>
            <li><strong>Email :</strong> {{ $user->email }}</li>
            <li><strong>Nom :</strong> {{ $user->firstname }} {{ $user->lastname }}</li>
        </ul>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>© {{ date('Y') }} Walab - Tous droits réservés</p>
            <p>
                <a href="#">Politique de confidentialité</a> | 
                <a href="#">Conditions d'utilisation</a>
            </p>
        </div>
    </div>
</body>
</html>
