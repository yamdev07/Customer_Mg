<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Activite extends Model
{
    protected $table = 'activites';

    protected $fillable = [
        'user_id',
        'client_id',
        'action',
        'description',
    ];

    /**
     * Métadonnées d'affichage par type d'action (icône + couleur de la charte).
     *
     * @var array<string, array{icon: string, color: string}>
     */
    public const META = [
        'created' => ['icon' => 'fa-user-plus',           'color' => 'anyxtech'],
        'updated' => ['icon' => 'fa-pen',                 'color' => 'info'],
        'deleted' => ['icon' => 'fa-trash',               'color' => 'danger'],
        'paid' => ['icon' => 'fa-circle-check',        'color' => 'success'],
        'reconnected' => ['icon' => 'fa-plug',                'color' => 'success'],
        'disconnected' => ['icon' => 'fa-plug-circle-xmark',   'color' => 'warning'],
        'suspended' => ['icon' => 'fa-pause',               'color' => 'warning'],
        'reactivated' => ['icon' => 'fa-play',                'color' => 'success'],
        'notified' => ['icon' => 'fa-paper-plane',         'color' => 'info'],
    ];

    /* ──────────────────────────────────────────────
       Enregistrement
    ────────────────────────────────────────────── */

    /**
     * Journaliser une action importante. N'interrompt jamais le flux principal.
     */
    public static function log(string $action, string $description, ?Client $client = null): void
    {
        try {
            static::create([
                'user_id' => Auth::id(),
                'client_id' => $client?->id,
                'action' => $action,
                'description' => $description,
            ]);
        } catch (\Throwable $e) {
            // Le journal ne doit jamais casser une opération métier.
            Log::warning('Échec journalisation activité : '.$e->getMessage());
        }
    }

    /* ──────────────────────────────────────────────
       Relations
    ────────────────────────────────────────────── */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'Système']);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /* ──────────────────────────────────────────────
       Accessors d'affichage
    ────────────────────────────────────────────── */

    public function getIconAttribute(): string
    {
        return self::META[$this->action]['icon'] ?? 'fa-circle-info';
    }

    public function getColorAttribute(): string
    {
        return self::META[$this->action]['color'] ?? 'anyxtech';
    }
}
