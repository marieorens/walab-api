<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commande</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .order-code {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .info-section {
            margin: 20px 0;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        .info-value {
            color: #6c757d;
            margin-bottom: 15px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            <h1>Nouvelle Commande Reçue</h1>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $laboratoire ? $laboratoire->name : 'Laboratoire' }},</p>
            
            <p>Une nouvelle commande vient d'être passée et nécessite votre attention.</p>
            
            <div class="order-code">
                Code de commande : {{ $commande->code }}
            </div>

            <div class="info-section">
                <div class="info-label">Statut</div>
                <div class="info-value">
                    <span class="status-badge status-pending">En attente</span>
                </div>
            </div>

            <div class="info-section">
                <div class="info-label">Client</div>
                <div class="info-value">
                    {{ $client->firstname }} {{ $client->lastname }}<br>
                    {{ $client->email }}<br>
                    @if($client->phone)
                        {{ $client->phone }}
                    @endif
                </div>
            </div>

            @if($commande->adress)
            <div class="info-section">
                <div class="info-label">Adresse de prélèvement</div>
                <div class="info-value">{{ $commande->adress }}</div>
            </div>
            @endif

            @if($commande->date_prelevement)
            <div class="info-section">
                <div class="info-label">Date de prélèvement souhaitée</div>
                <div class="info-value">{{ $commande->date_prelevement }}</div>
            </div>
            @endif

            @if(count($items) > 0)
            <div class="info-section">
                <div class="info-label">Articles commandés</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Désignation</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item['type'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ number_format($item['price'], 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if($commande->description)
            <div class="info-section">
                <div class="info-label">Description / Notes</div>
                <div class="info-value">{{ $commande->description }}</div>
            </div>
            @endif

            <div class="info-section">
                <div class="info-label">Paiement</div>
                <div class="info-value">
                    {{ $commande->payed ? '✅ Payé (' . number_format($commande->amount, 0, ',', ' ') . ' FCFA)' : '⏳ En attente de paiement' }}
                </div>
            </div>

            <center>
                <a href="{{ config('app.frontend_url', 'http://localhost:5173') }}/laboratoire/commande" class="button">
                    Voir la commande
                </a>
            </center>

            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                Cette commande apparaît également dans votre tableau de bord. Vous pouvez l'assigner à un agent pour traitement.
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} WaLab - Plateforme de gestion de laboratoires</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
