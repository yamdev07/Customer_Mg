<?php

use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\Client\ClientCrudController;
use App\Http\Controllers\Client\ClientExportController;
use App\Http\Controllers\Client\ClientFilteredListsController;
use App\Http\Controllers\Client\ClientListController;
use App\Http\Controllers\Client\ClientNotificationController;
use App\Http\Controllers\Client\ClientPaymentController;
use App\Http\Controllers\Client\ClientStatusController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Page d'accueil publique ──
Route::get('/', fn () => view('welcome'));

// ── Routes protégées par auth ──
Route::middleware(['auth'])->group(function () {

    // Dashboard → on arrive directement sur les clients actifs
    Route::get('/dashboard', fn () => redirect()->route('clients.actifs'))->name('dashboard');

    // ──────────────────────────────────────────────
    //  Clients — Commercial & Admin (lecture + ajout)
    // ──────────────────────────────────────────────
    Route::middleware(['role:commercial,admin'])->group(function () {
        Route::get('/clients', ClientListController::class)->name('clients.index');
        Route::get('/clients/create', [ClientCrudController::class, 'create'])->name('clients.create');
        Route::post('/clients', [ClientCrudController::class, 'store'])->name('clients.store');

        // Listes filtrées — DOIVENT être déclarées AVANT la route générique
        // /clients/{client}, sinon « payes », « actifs »… sont pris pour un id.
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/payes', [ClientFilteredListsController::class, 'payes'])->name('payes');
            Route::get('/nonpayes', [ClientFilteredListsController::class, 'nonPayes'])->name('nonpayes');
            Route::get('/actifs', [ClientFilteredListsController::class, 'actifs'])->name('actifs');
            Route::get('/suspendus', [ClientFilteredListsController::class, 'suspendus'])->name('suspendus');
            Route::get('/reabonnement', [ClientFilteredListsController::class, 'aReabonnement'])->name('reabonnement');
            Route::get('/depasses', [ClientFilteredListsController::class, 'depasses'])->name('depasses');
        });

        // Journal d'activité (notifications)
        Route::get('/activites', ActiviteController::class)->name('activites.index');

        // Route générique en dernier (capture-tout sur un id de client)
        Route::get('/clients/{client}', [ClientCrudController::class, 'show'])->name('clients.show');
    });

    // ──────────────────────────────────────────────
    //  Clients — Admin uniquement (écriture)
    // ──────────────────────────────────────────────
    Route::middleware(['role:admin'])->prefix('clients')->name('clients.')->group(function () {
        // CRUD
        Route::get('/{client}/edit', [ClientCrudController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientCrudController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientCrudController::class, 'destroy'])->name('destroy');

        // Paiement
        Route::patch('/{client}/marquer-paye', [ClientPaymentController::class, 'markAsPaid'])->name('marquer-paye');
        Route::patch('/{client}/reconnecter', [ClientPaymentController::class, 'reconnect'])->name('reconnecter');
        Route::post('/{client}/deconnecter', [ClientPaymentController::class, 'disconnect'])->name('deconnecter');

        // Statut
        Route::patch('/{client}/suspendre', [ClientStatusController::class, 'suspend'])->name('suspendre');
        Route::patch('/{client}/reactiver', [ClientStatusController::class, 'reactivate'])->name('reactiver');

        // Notifications
        Route::post('/{client}/relancer', [ClientNotificationController::class, 'sendWhatsApp'])->name('relancer');
        Route::get('/notifier', [ClientNotificationController::class, 'sendEmailNotifications'])->name('notifier');
        Route::get('/envoyer-notifications', [ClientNotificationController::class, 'sendEmailNotifications'])->name('envoyerNotifications');
        Route::post('/{client}/relancer-sms', [ClientNotificationController::class, 'sendSms'])->name('relancerSms');

        // Export
        Route::post('/export', [ClientExportController::class, 'exportPdf'])->name('export');
        Route::get('/export/pdf', [ClientExportController::class, 'exportActivePdf'])->name('exportPdf');
    });

    // ──────────────────────────────────────────────
    //  Profil utilisateur
    // ──────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Auth Laravel Breeze ──
require __DIR__.'/auth.php';
