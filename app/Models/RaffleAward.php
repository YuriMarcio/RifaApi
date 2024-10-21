<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaffleAward extends Model
{
    use HasFactory;

    protected $table = 'raffle_award'; // Nome da tabela de ligação

    protected $fillable = [
        'raffle_id',
        'award_id',
    ];
}
