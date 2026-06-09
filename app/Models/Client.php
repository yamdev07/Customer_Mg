<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    // ── Statuts possibles ──
    public const STATUS_ACTIF = 'actif';

    public const STATUS_INACTIF = 'inactif';

    public const STATUS_SUSPENDU = 'suspendu';

    protected $table = 'clients';

    protected $fillable = [
        'nom_client',
        'contact',
        'sites_relais',
        'statut',
        'categorie',
        'date_reabonnement',
        'jour_reabonnement',
        'montant',
        'a_paye',
        'email',
        'lieu',
    ];

    protected $casts = [
        'a_paye' => 'boolean',
        'date_reabonnement' => 'date',
        'montant' => 'decimal:2',
        'jour_reabonnement' => 'integer',
    ];

    /* ──────────────────────────────────────────────
       Relations
    ────────────────────────────────────────────── */

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    /* ──────────────────────────────────────────────
       Scopes
    ────────────────────────────────────────────── */

    public function scopeActifs(Builder $query): Builder
    {
        return $query->where('statut', self::STATUS_ACTIF);
    }

    public function scopeSuspendus(Builder $query): Builder
    {
        return $query->where('statut', self::STATUS_SUSPENDU);
    }

    public function scopePayesPourMois(Builder $query, int $mois, int $annee): Builder
    {
        return $query->whereHas('paiements', fn ($q) => $q
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->where('statut', true)
        );
    }

    public function scopeNonPayesPourMois(Builder $query, int $mois, int $annee): Builder
    {
        return $query->whereDoesntHave('paiements', fn ($q) => $q
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->where('statut', true)
        );
    }

    public function scopeReabonnementProche(Builder $query, int $jours = 5): Builder
    {
        $today = Carbon::today();

        return $query->whereDate('date_reabonnement', '<=', $today->copy()->addDays($jours))
            ->whereDate('date_reabonnement', '>=', $today);
    }

    public function scopeReabonnementDepasse(Builder $query): Builder
    {
        return $query->whereDate('date_reabonnement', '<', Carbon::today());
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        $search = strtolower($search);

        return $query->where(function ($q) use ($search) {
            $q->whereRaw('LOWER(nom_client) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(contact) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(sites_relais) LIKE ?', ["%{$search}%"]);
        });
    }

    /* ──────────────────────────────────────────────
       Attributes / Accessors
    ────────────────────────────────────────────── */

    public function getProchainMoisDuAttribute(): string
    {
        $dernierPaiement = $this->paiements()
            ->where('statut', true)
            ->latest('annee')
            ->latest('mois')
            ->first();

        if ($dernierPaiement) {
            return Carbon::create($dernierPaiement->annee, $dernierPaiement->mois, 1)
                ->addMonth()
                ->format('m/Y');
        }

        return 'Non payé';
    }

    /* ──────────────────────────────────────────────
       Business helpers
    ────────────────────────────────────────────── */

    public function estPayePourMois(int $mois, int $annee): bool
    {
        return $this->paiements()
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->where('statut', true)
            ->exists();
    }

    public function moisImpayeLePlusAncien(): ?Paiement
    {
        return $this->paiements()
            ->where('statut', false)
            ->orderBy('annee')
            ->orderBy('mois')
            ->first();
    }

    public function prochainMoisDu(): Carbon
    {
        $moisImpaye = $this->moisImpayeLePlusAncien();

        if ($moisImpaye) {
            return Carbon::create($moisImpaye->annee, $moisImpaye->mois, $this->jour_reabonnement ?? 1);
        }

        $dernierPaiement = $this->paiements()
            ->orderByDesc('annee')
            ->orderByDesc('mois')
            ->first();

        if ($dernierPaiement) {
            $mois = $dernierPaiement->mois + 1;
            $annee = $dernierPaiement->annee;
            if ($mois > 12) {
                $mois = 1;
                $annee++;
            }

            return Carbon::create($annee, $mois, $this->jour_reabonnement ?? 1);
        }

        return Carbon::now();
    }
}
