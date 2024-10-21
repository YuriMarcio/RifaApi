<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrlAndIsEnableToRafflesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raffles', function (Blueprint $table) {
            // Adicionando as novas colunas
            $table->string('url')->nullable()->after('paymentMethod'); // URL pode ser nulo
            $table->boolean('isEnable')->default(true)->after('url'); // isEnable com valor padrão true
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
            // Removendo as colunas caso a migration seja revertida
            $table->dropColumn('url');
            $table->dropColumn('isEnable');
        });
    }
}
