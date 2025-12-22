<?php

namespace App\Observers;

use App\Enum\StatutPaiementEnum;
use App\Models\Paiement;
use App\Services\CommissionService;

class PaiementObserver
{
    protected CommissionService $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function created(Paiement $paiement): void
    {
        if (in_array($paiement->status, [StatutPaiementEnum::PAYER->value, StatutPaiementEnum::PHYSICAL->value])) {
            $this->commissionService->processCommission($paiement);
        }
    }

    public function updated(Paiement $paiement): void
    {
        if ($paiement->wasChanged('status') && 
            in_array($paiement->status, [StatutPaiementEnum::PAYER->value, StatutPaiementEnum::PHYSICAL->value]) && 
            !$paiement->commission_processed) {
            $this->commissionService->processCommission($paiement);
        }
    }
}
