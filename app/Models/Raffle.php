<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raffle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'raffleType',
        'startDate',
        'endDate',
        'description',
        'terms',
        'ticketPrice',
        'maxTicketsPerUser',
        'maxParticipants',
        'paymentMethod',
        'url',
        'isEnable'
    ];

    /**
     * A Raffle pode ter muitos Prêmios.
     */
    public function awards()
    {
        return $this->belongsToMany(Award::class, 'raffle_award', 'raffle_id', 'award_id');
    }
}

