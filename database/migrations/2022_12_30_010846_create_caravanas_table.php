<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravanas', function (Blueprint $table) {
            $table->id('id');
            $table->string('Nome', 255);
            $table->string('Destino', 255);
            $table->integer('Quantidade_passageiros');
            $table->dateTime('DataHora_partida');
            $table->dateTime('DataHora_retorno');
            $table->enum('Status', ['Ativa', 'Inativa']);
            $table->unsignedBigInteger('estacas_id');
            $table->foreign('estacas_id')->references('id')->on('estacas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caravanas');
    }
}
