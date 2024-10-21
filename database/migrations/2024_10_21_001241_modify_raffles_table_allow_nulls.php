<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyRafflesTableAllowNulls extends Migration
{
    public function up(): void
    {
        Schema::table('raffles', function (Blueprint $table) {
            // Permite valores nulos nas colunas especificadas
            $table->dateTime('startDate')->nullable()->change(); // Permite nulo
            $table->dateTime('endDate')->nullable()->change(); // Permite nulo
            $table->text('terms')->nullable()->change(); // Permite nulo
            $table->text('description')->nullable()->change(); // Permite nulo
        });
    }

    public function down(): void
    {
        Schema::table('raffles', function (Blueprint $table) {
            // Reverte as alterações, tornando as colunas não nulas novamente
            $table->dateTime('startDate')->nullable(false)->change();
            $table->dateTime('endDate')->nullable(false)->change();
            $table->text('terms')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
        });
    }
}
