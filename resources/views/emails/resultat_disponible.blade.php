<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats Disponibles</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 400;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #555;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .info-label {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .info-value {
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }
        .password-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            margin: 30px 0;
        }
        .password-label {
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        .password-value {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
            padding: 15px;
            background-color: rgba(255,255,255,0.2);
            border-radius: 6px;
            display: inline-block;
        }
        .security-notice {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 25px 0;
            font-size: 14px;
        }
        .security-notice strong {
            display: block;
            margin-bottom: 5px;
        }
        .instructions {
            background-color: #e7f3ff;
            padding: 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .instructions h3 {
            margin-top: 0;
            color: #1976D2;
            font-size: 16px;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
            color: #555;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            font-size: 13px;
            color: #666;
            border-top: 1px solid #e9ecef;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🔒</div>
            <h1>Résultats Disponibles</h1>
        </div>
        
        <div class="content">
            <p class="greeting">Bonjour <strong>{{ $clientName }}</strong>,</p>
            
            <p class="message">
                Vos résultats médicaux pour la commande <strong>#{{ $commande->code }}</strong> 
                sont maintenant disponibles. Pour garantir la confidentialité de vos données de santé, 
                votre document PDF a été automatiquement crypté.
            </p>

            <div class="info-box">
                <div class="info-label">Commande</div>
                <div class="info-value">#{{ $commande->code }}</div>
            </div>

            <div class="password-box">
                <div class="password-label">Votre code de déchiffrement :</div>
                <div class="password-value">{{ $pdfPassword }}</div>
            </div>

            <div class="security-notice">
                <strong>Important - Sécurité</strong>
                Ce code est unique et confidentiel. Ne le partagez avec personne. 
                Il vous sera demandé lors de l'ouverture du fichier PDF.
            </div>

            <div class="instructions">
                <h3>Comment accéder à vos résultats ?</h3>
                <ol>
                    <li>Connectez-vous à votre espace patient sur WaLab</li>
                    <li>Accédez à vos commandes et téléchargez le PDF</li>
                    <li>Ouvrez le fichier PDF avec votre lecteur habituel</li>
                    <li>Entrez le code ci-dessus lorsqu'il vous sera demandé</li>
                    <li>Vous pouvez maintenant consulter vos résultats en toute sécurité</li>
                </ol>
            </div>

            <p class="message" style="margin-top: 30px;">
                <strong>Conseil :</strong> Conservez ce code en lieu sûr. 
                Vous pouvez également le retrouver dans votre espace patient.
            </p>

            <p class="message" style="font-size: 14px; color: #777;">
                En cas de perte du code ou pour toute question concernant vos résultats, 
                n'hésitez pas à contacter votre laboratoire ou notre support.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                <strong>WaLab - Plateforme de Gestion Médicale</strong>
            </p>
            <p style="margin: 0;">
                Vos données de santé sont protégées et sécurisées.<br>
                <a href="#">Politique de confidentialité</a> | 
                <a href="#">Conditions d'utilisation</a>
            </p>
        </div>
    </div>
</body>
</html>
