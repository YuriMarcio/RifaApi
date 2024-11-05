<?php

use App\Enums\RaffleType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRafflesTableAddRaffleTypeEnum extends Migration
{
    public function up(): void
    {
        Schema::table('raffles', function (Blueprint $table) {
            // Se você estiver usando um enum, pode ser assim:
            $table->enum('raffleType', [RaffleType::WINNING_TICKET->value])->default('winning_ticket')->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('raffles', function (Blueprint $table) {
            $table->dropColumn('raffleType');
        });
    }
}
