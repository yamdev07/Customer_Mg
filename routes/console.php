<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Synchronisation quotidienne des statuts clients (payé / suspendu).
Schedule::command('clients:synchroniser-statuts')->dailyAt('00:30');

// Mise à jour mensuelle des dates de réabonnement.
Schedule::command('clients:mettre-a-jour-reabonnement')->monthlyOn(1, '00:15');
