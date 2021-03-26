<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinoCreditoMoralesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destino_credito_morales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_credito_moral');
            $table->unsignedBigInteger('id_destino_recursos');
            $table->text('titular');
            $table->text('numero_cuenta_clabe');
            $table->integer('tipo_cuenta');
            $table->foreign('id_credito_moral')->references('id')->on('credito_morales');
            $table->foreign('id_destino_recursos')->references('id')->on('destino_recursos');
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
        Schema::dropIfExists('destino_credito_morales');
    }
}
