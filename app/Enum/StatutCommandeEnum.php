<?php

namespace App\Enum;

enum StatutCommandeEnum: string
{
    // Encours de paiement
    case PENDING = 'En attente';

    // En attente de traitement
    case IN_PROGRESS = 'En cours';

    // Fin des operations
    case FINISH = 'Terminer';
}
