<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('endereço', 45);
            $table->unsignedBigInteger('estacas_id');
            //foreign key 
            $table->foreign('estacas_id')->references('id')->on('estacas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alas');
    }
}
