# Walab API

## Configuration du CRON pour WEBSOCKET
<p align="center"><a href="https://aladecouvertedubenin.com" target="_blank"><img src="public/assets/images/logo.png" width="100"></a></p>

### Choisir un éditeur de texte:
Utiliser un éditeur de texte comme <code> nano, vim </code> ou un éditeur graphique comme <code>gedit</code>.

### Créer un nouveau fichier Cron:
Ouvrir un terminal et exécuter la commande suivante (remplacer <code>/chemin/vers/votre/projet</code> par le chemin réel de votre projet Laravel):

    crontab -e

### Ajouter une nouvelle ligne
Ajouter la ligne suivante à la fin du fichier, en adaptant le chemin vers votre projet et la fréquence d'exécution (ici, toutes les 5 minutes)

    */5 * * * * cd /chemin/vers/votre/projet && (pgrep -f "php artisan queue:work" >/dev/null || php artisan queue:work) && (pgrep -f "php artisan reverb:start" >/dev/null || php artisan reverb:start --debug --host 0.0.0.0)

### Explication de la ligne
- <code>*/5 * * * *</code>: Exécuter toutes les 5 minutes.
- <code>cd /chemin/vers/votre/projet</code>: Changer de répertoire vers votre projet.
- <code>pgrep -f "php artisan queue:work" >/dev/null</code>: Vérifie si le processus "php artisan queue:work" est en cours d'exécution. Si ce n'est pas le cas, la commande est exécutée.
- <code>pgrep -f "php artisan reverb:start" >/dev/null || php artisan reverb:start --debug --host 0.0.0.0</code>: Même logique pour "php artisan reverb:start"