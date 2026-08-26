<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Connexion extends Model
{
    protected $table = 'connexions';

    protected $fillable = [
        'client_id',
        'service_id',
        'date_connexion',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'date_connexion' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scopePourService($query, int $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    public function scopePourClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeDernieres($query, int $limit = 10)
    {
        return $query->orderByDesc('date_connexion')->limit($limit);
    }
}
