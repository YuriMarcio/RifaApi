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
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título do prêmio
            $table->string('description')->nullable(); // Descrição do prêmio
            $table->enum('tagType', ['Product', 'Money']); // Tipo de prêmio
            $table->decimal('value', 10, 2)->nullable(); // Valor do prêmio em dinheiro (se for Money)
            $table->json('images')->nullable(); // Armazena várias imagens (caso seja um produto)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awards');
    }
};
