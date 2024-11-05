<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    // Relação com a rifa
    public function rifa()
    {
        return $this->belongsTo(Raffle::class);
    }

    // Relação com usuários (muitos para muitos)
    public function users()
    {
        return $this->belongsToMany(User::class, 'ticket_user');
    }
}
