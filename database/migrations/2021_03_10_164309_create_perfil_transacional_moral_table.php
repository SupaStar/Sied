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
            $table->string('monto',191);
            $table->string('tcredito',191);
            $table->string('frecuencia',191);
            $table->string('actividad',191);
            $table->string('propietario',191);
            $table->string('proovedor',191);
            $table->string('dactividad',191);
            $table->string('dpasivos',191);
            $table->string('dotro',191);
            $table->string('total',191);
            $table->string('aceptable',191);
            $table->string('dificil',191);
            $table->string('conducta',191);
            $table->string('comentario',191);
            $table->unsignedBigInteger('id_moral');
            $table->unsignedBigInteger('origen_recursos');
            $table->unsignedBigInteger('destino_recursos');
            $table->unsignedBigInteger('instrumento_monetario');
            $table->unsignedBigInteger('divisas');
            $table->unsignedBigInteger('profesion');
            $table->unsignedBigInteger('actividad_giro');
            $table->unsignedBigInteger('pId')->default(2);
            $table->unsignedBigInteger('efr')->default(2);
            $table->float('ingreso',10,2);
            $table->integer('nPagoMes')->default(1);
            $table->foreign('id_moral')->references('id')->on('morales');
            $table->foreign('origen_recursos')->references('id')->on('origen_recursos');
            $table->foreign('destino_recursos')->references('id')->on('destino_recursos');
            $table->foreign('instrumento_monetario')->references('id')->on('instrumento_monetario');
            $table->foreign('divisas')->references('id')->on('divisa');
            $table->foreign('profesion')->references('id')->on('profesion');
            $table->foreign('actividad_giro')->references('id')->on('actividad_giro');
            $table->foreign('efr')->references('id')->on('entidad_federativa_residencia');
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
