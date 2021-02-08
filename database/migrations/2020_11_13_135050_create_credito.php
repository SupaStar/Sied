<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCredito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credito', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('client_id');
          $table->string('tcredito');
          $table->string('contrato');
          $table->decimal('monto',10,2);
          $table->string('fpago');
          $table->string('frecuencia');
          $table->integer('plazo');
          $table->string('amortizacion');
          $table->string('iva');
          $table->decimal('tasa',10,2);
          $table->date('disposicion');
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
        Schema::dropIfExists('credito');
    }
}
