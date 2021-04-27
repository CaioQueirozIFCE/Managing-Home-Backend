<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('descricao')->max('100');
            $table->float('valor');
            $table->tinyInteger('tipo')->default(0);
            $table->tinyInteger('frequencia')->default(0);
            $table->date('data');
            $table->timestamps();//retirar timestamp
            $table->unsignedBigInteger('id_user');

            $table->foreign('id_user')->references('id')->on('users');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
