<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;

class CommandController extends Controller
{
    public function runCommands()
    {
        // // Exécuter la première commande en arrière-plan
        // Process::start('php artisan queue:work');

        // // Exécuter la deuxième commande en arrière-plan
        // Process::start('php artisan reverb:start --host 0.0.0.0');

         // Exécuter la première commande en arrière-plan et capturer le PID
        $queueCommand = 'php artisan queue:work > /storage/logs/queue_work_output.log 2>&1 & echo $!';
        $queueOutput = shell_exec("nohup " . $queueCommand);
        $queuePid = trim($queueOutput);

        // Exécuter la seconde commande en arrière-plan et capturer le PID
        $reverbCommand = 'php artisan reverb:start --debug --host 0.0.0.0 > /storage/logs/reverb_start_output.log 2>&1 & echo $!';
        $reverbOutput = shell_exec("nohup " . $reverbCommand);
        $reverbPid = trim($reverbOutput);

        return response()->json([
            'queue_pid' => $queuePid,
            'reverb_pid' => $reverbPid,
            'message' => 'Les commandes artisan sont exécutées en arrière-plan',
        ]);
    }
}
