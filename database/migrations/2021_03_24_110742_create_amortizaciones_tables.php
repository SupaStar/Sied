<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmortizacionesTables extends Migration
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
            $table->unsignedBigInteger('moral_id');
            $table->string('tcredito',191);
            $table->string('contrato',191);
            $table->decimal('monto',10,2);
            $table->string('fpago',191);
            $table->string('frecuencia',191);
            $table->integer('plazo');
            $table->string('amortizacion',191);
            $table->string('iva',191);
            $table->decimal('tasa',10,2);
            $table->date('disposicion');
            $table->foreign('moral_id')->references('id')->on('morales');
            $table->timestamps();
        });
      Schema::create('amortizaciones_morales', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('moral_id');
        $table->unsignedBigInteger('credito_id');
        $table->integer('periodo');
        $table->string('fechas',191);
        $table->date('inicio');
        $table->date('fin');
        $table->integer('dias');
        $table->decimal('disposicion',10);
        $table->decimal('saldo_insoluto',10);
        $table->decimal('comision',10);
        $table->decimal('amortizacion',10);
        $table->decimal('intereses',10);
        $table->decimal('moratorios',10);
        $table->decimal('iva',10);
        $table->decimal('flujo',10);
        $table->integer('dias_mora');
        $table->decimal('int_mora',10);
        $table->decimal('pagos',10);
        $table->decimal('gcobranza',10,2);
        $table->integer('liquidado')->default(0);
        $table->decimal('iva_mora',10,2);
        $table->date('dia_mora');
        $table->foreign('moral_id')->references('id')->on('morales');
        $table->foreign('credito_id')->references('id')->on('credito_morales');
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
        Schema::dropIfExists('amortizaciones_tables');
    }
}
