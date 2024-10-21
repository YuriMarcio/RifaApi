<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'tagType', // Product ou Money
        'value', // Para prêmios em dinheiro
        'image', // Para a imagem do prêmio, se houver
    ];

    /**
     * Um Prêmio pode estar em muitas Rifas.
     */
    public function raffles()
    {
        return $this->belongsToMany(Raffle::class, 'raffle_award', 'award_id', 'raffle_id');
    }
}

