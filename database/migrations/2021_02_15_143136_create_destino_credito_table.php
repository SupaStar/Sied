<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinoCreditoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destino_credito', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('id_credito');
          $table->foreign("id_credito")->references("id")->on("credito");
          $table->unsignedBigInteger('id_destino_recursos');
          $table->foreign("id_destino_recursos")->references("id")->on("destino_recursos");
          $table->text('titular');
          $table->text('numero_cuenta_clabe');
          $table->integer('tipo_cuenta');
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
        Schema::dropIfExists('destino_credito');
    }
}
