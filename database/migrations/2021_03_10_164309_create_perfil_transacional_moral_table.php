<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerfilTransacionalMoralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfil_transacional_moral', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('monto',191)->nullable();
            $table->string('tcredito',191)->nullable();
            $table->string('frecuencia',191)->nullable();
            $table->string('actividad',191)->nullable();
            $table->string('propietario',191)->nullable();
            $table->string('proovedor',191)->nullable();
            $table->string('dactividad',191)->nullable();
            $table->string('dpasivos',191)->nullable();
            $table->string('dotro',191)->nullable();
            $table->string('total',191)->nullable();
            $table->string('aceptable',191)->nullable();
            $table->string('dificil',191)->nullable();
            $table->string('conducta',191)->nullable();
            $table->string('comentario',191)->nullable();
            $table->unsignedBigInteger('id_moral');
            $table->unsignedBigInteger('origen_recursos')->nullable();
            $table->unsignedBigInteger('destino_recursos')->nullable();
            $table->unsignedBigInteger('instrumento_monetario')->nullable();
            $table->unsignedBigInteger('divisas')->nullable();
            $table->unsignedBigInteger('profesion')->nullable();
            $table->unsignedBigInteger('actividad_giro')->nullable();
            $table->unsignedBigInteger('pId')->default(2);
            $table->unsignedBigInteger('efr')->nullable();
            $table->float('ingreso',10,2)->nullable();
            $table->integer('nPagoMes')->default(1);
//            $table->foreign('id_moral')->references('id')->on('morales');
//            $table->foreign('origen_recursos')->references('id')->on('origen_recursos');
//            $table->foreign('destino_recursos')->references('id')->on('destino_recursos');
//            $table->foreign('instrumento_monetario')->references('id')->on('instrumento_monetario');
//            $table->foreign('divisas')->references('id')->on('divisa');
//            $table->foreign('profesion')->references('id')->on('profesion');
//            $table->foreign('actividad_giro')->references('id')->on('actividad_giro');
//            $table->foreign('efr')->references('id')->on('entidad_federativa_residencia');
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
        Schema::dropIfExists('perfil_transacional_moral');
    }
}
