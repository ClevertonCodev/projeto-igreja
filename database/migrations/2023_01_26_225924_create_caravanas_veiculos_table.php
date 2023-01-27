<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanasVeiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravanas_veiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caravanas_id');
            $table->foreign('caravanas_id')->references('id')->on('caravanas');
            $table->unsignedBigInteger('veiculos_id');
            $table->foreign('veiculos_id')->references('id')->on('veiculos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caravanas_veiculos');
    }
}
