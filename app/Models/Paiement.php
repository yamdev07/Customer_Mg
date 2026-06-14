<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    protected $fillable = [
        'client_id',
        'mois',
        'annee',
        'montant',
        'date_paiement',
        'statut',
    ];

    protected $casts = [
        'statut' => 'boolean',
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
        'mois' => 'integer',
        'annee' => 'integer',
    ];

    /* ──────────────────────────────────────────────
       Relations
    ────────────────────────────────────────────── */

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /* ──────────────────────────────────────────────
       Scopes
    ────────────────────────────────────────────── */

    public function scopePayes(Builder $query): Builder
    {
        return $query->where('statut', true);
    }

    public function scopeImpayes(Builder $query): Builder
    {
        return $query->where('statut', false);
    }

    public function scopePourMois(Builder $query, int $mois, int $annee): Builder
    {
        return $query->where('mois', $mois)->where('annee', $annee);
    }

    public function scopePourClient(Builder $query, int $clientId): Builder
    {
        return $query->where('client_id', $clientId);
    }

    /* ──────────────────────────────────────────────
       Attributes
    ────────────────────────────────────────────── */

    public function getMoisLibelleAttribute(): string
    {
        return Carbon::createFromDate($this->annee, $this->mois, 1)->translatedFormat('F Y');
    }
}
