<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::command('wallets:generate-withdrawals --notify')
    ->monthlyOn(1, '00:05')
    ->timezone('Africa/Porto-Novo')
    ->withoutOverlapping()
    ->onSuccess(function () {
        \Log::info('Retraits mensuels générés avec succès');
    })
    ->onFailure(function () {
        \Log::error('Échec de la génération des retraits mensuels');
    });
