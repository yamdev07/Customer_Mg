<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = ['nom', 'description', 'prix_defaut', 'couleur', 'actif'];

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class)
            ->withPivot('montant', 'jour_reabonnement', 'date_reabonnement', 'a_paye')
            ->withTimestamps();
    }

    public function clientsActifs(): BelongsToMany
    {
        return $this->clients()->wherePivot('a_paye', true);
    }

    public function clientsNonPayes(): BelongsToMany
    {
        return $this->clients()->wherePivot('a_paye', false);
    }
}
