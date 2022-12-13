<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255);
            $table->string('password', 255);
            $table->string('email' , 100);
            $table->unsignedTinyInteger('active');
            $table->enum('type', ['comum', 'secretarios', 'super']);
            $table->string('rg', 20);
            $table->string('cpf', 11);
            $table->string('telefone', 11);
            $table->string('endereço', 155);
            $table->unsignedBigInteger('alas_id')->nullable($value = true);
            $table->timestamps();
            
            //foreign key 
            $table->foreign('alas_id')->references('id')->on('alas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
