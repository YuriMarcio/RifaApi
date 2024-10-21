<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIsEnableDefaultValueInRafflesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raffles', function (Blueprint $table) {
            // Alterando o valor padrão da coluna isEnable para false
            $table->boolean('isEnable')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raffles', function (Blueprint $table) {
            // Revertendo a alteração para o valor padrão true
            $table->boolean('isEnable')->default(true)->change();
        });
    }
}
