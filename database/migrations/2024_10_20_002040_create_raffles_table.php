<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título da rifa
            $table->string('raffleType'); // Tipo de rifa
            $table->dateTime('startDate'); // Data de início
            $table->dateTime('endDate'); // Data de término
            $table->text('description')->nullable(); // Descrição da rifa
            $table->text('terms')->nullable(); // Termos e condições
            $table->decimal('ticketPrice', 10, 2); // Preço do ticket
            $table->integer('maxTicketsPerUser'); // Máximo de tickets por usuário
            $table->integer('maxParticipants'); // Máximo de participantes
            $table->string('paymentMethod'); // Método de pagamento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raffles');
    }
};
