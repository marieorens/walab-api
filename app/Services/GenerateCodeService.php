<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Support\Str;

class GenerateCodeService
{
    protected $commande;

    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    public function generate()
    {
        do {
            $code = Str::random(15);
        } while ($this->commande->where('code', $code)->first());

        return $code;
    }
}
