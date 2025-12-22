<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[Admin] Nouvelle Commande</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .info-section {
            margin: 15px 0;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        .info-value {
            color: #6c757d;
            margin-bottom: 10px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Nouvelle Commande - Notification Admin</h1>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <strong>📋 Code de commande : {{ $commande->code }}</strong>
            </div>

            <div class="info-section">
                <div class="info-label">Laboratoire concerné</div>
                <div class="info-value">
                    🏥 {{ $laboratoire ? $laboratoire->name : 'Non spécifié' }}
                </div>
            </div>

            <div class="info-section">
                <div class="info-label">Client</div>
                <div class="info-value">
                    👤 {{ $client->firstname }} {{ $client->lastname }}<br>
                    📧 {{ $client->email }}
                </div>
            </div>

            @if($commande->adress)
            <div class="info-section">
                <div class="info-label">Adresse de prélèvement</div>
                <div class="info-value">📍 {{ $commande->adress }}</div>
            </div>
            @endif

            @if($commande->date_prelevement)
            <div class="info-section">
                <div class="info-label">Date de prélèvement</div>
                <div class="info-value">📅 {{ $commande->date_prelevement }}</div>
            </div>
            @endif

            <div class="info-section">
                <div class="info-label">Montant</div>
                <div class="info-value">
                    💰 {{ number_format($commande->amount, 0, ',', ' ') }} FCFA
                    @if($commande->payed)
                        <span style="color: green;">✅ Payé</span>
                    @else
                        <span style="color: orange;">⏳ En attente</span>
                    @endif
                </div>
            </div>

            <center>
                <a href="{{ config('app.url') }}/dashboard" class="button">
                    Voir dans le dashboard
                </a>
            </center>

            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                Cette notification est envoyée automatiquement aux administrateurs pour les tenir informés de l'activité de la plateforme.
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} WaLab - Administration</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
