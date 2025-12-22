<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WithdrawalsPendingNotification;
use App\Services\WalletService;
use Illuminate\Console\Command;

class GenerateMonthlyWithdrawals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallets:generate-withdrawals 
                            {--periode= : La période au format Y-m (ex: 2025-11). Par défaut: mois précédent}
                            {--notify : Notifier les admins des retraits générés}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les retraits mensuels pour tous les laboratoires ayant un solde positif';

    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        parent::__construct();
        $this->walletService = $walletService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $periode = $this->option('periode') ?? now()->subMonth()->format('Y-m');
        
        $this->info("🔄 Génération des retraits pour la période: {$periode}");
        $this->newLine();

        try {
            $results = $this->walletService->generateMonthlyWithdrawals($periode);

            $this->info("✅ Retraits générés avec succès!");
            $this->table(
                ['Métrique', 'Valeur'],
                [
                    ['Retraits créés', $results['created']],
                    ['Ignorés (solde nul ou déjà existant)', $results['skipped']],
                    ['Erreurs', $results['errors']],
                ]
            );

            // Notifier les admins si demandé
            if ($this->option('notify') && $results['created'] > 0) {
                $this->notifyAdmins($results['created'], $periode);
                $this->info("📧 Notification envoyée aux administrateurs.");
            }

            if ($results['errors'] > 0) {
                $this->warn("⚠️  Des erreurs se sont produites. Consultez les logs pour plus de détails.");
                return Command::FAILURE;
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la génération des retraits: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Notifie les administrateurs des nouveaux retraits
     */
    protected function notifyAdmins(int $count, string $periode): void
    {
        $admins = User::whereHas('role', function($q) {
            $q->whereIn('label', ['admin Sup', 'admin', 'Admin']);
        })->orWhere('role_id', 4)->get();

        foreach ($admins as $admin) {
            try {
                $admin->notify(new WithdrawalsPendingNotification($count, $periode));
            } catch (\Exception $e) {
                $this->warn("Impossible de notifier {$admin->email}: " . $e->getMessage());
            }
        }
    }
}
