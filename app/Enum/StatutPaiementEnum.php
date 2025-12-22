<?php

namespace App\Enum;

enum StatutPaiementEnum: string
{
    case PENDING = 'Pending';

    case PAYER = 'approved';

    case ECHEC = 'Echec';

    case PHYSICAL = 'physique';

}
