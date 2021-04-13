<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmortizacionesMorales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amortizaciones_morales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cliente_id');
            $table->bigInteger('credito_id');
            $table->Integer('periodo');
            $table->string('fechas')->nullable();
            $table->Date('inicio')->nullable();
            $table->Date('fin')->nullable();
            $table->Integer('dias')->nullable();
            $table->decimal('disposicion',10,2)->nullable();
            $table->decimal('saldo_insoluto',10,2)->nullable();
            $table->decimal('comision',10,2)->nullable();
            $table->decimal('amortizacion',10,2)->nullable();
            $table->decimal('intereses',10,2)->nullable();
            $table->decimal('moratorios',10,2)->nullable();
            $table->decimal('iva',10,2)->nullable();
            $table->decimal('flujo',10,2)->nullable();
            $table->Integer('dias_mora');
            $table->decimal('int_mora',10,2)->nullable();
            $table->decimal('pagos',10,2)->nullable();
            $table->decimal('gcobranza',10,2)->nullable();
            $table->integer('liquidado')->nullable()->default(0);
            $table->decimal('iva_mora',10,2)->nullable();
            $table->date('dia_mora')->nullable();
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
        Schema::dropIfExists('amortizaciones');
    }
}
