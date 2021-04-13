<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditoMorales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credito_morales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('morales_id');
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
            $table->string('status')->nullable()->default('Aprobado');
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
        Schema::dropIfExists('credito_morales');
    }
}
