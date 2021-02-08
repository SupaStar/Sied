<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerfilTransacional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfil_transacional', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('monto')->nullable();
            $table->string('tcredito')->nullable();
            $table->string('frecuencia')->nullable();
            $table->string('actividad')->nullable();
            $table->string('propietario')->nullable();
            $table->string('proovedor')->nullable();
            $table->string('dactividad')->nullable();
            $table->string('dpasivos')->nullable();
            $table->string('dotro')->nullable();
            $table->string('total')->nullable();
            $table->string('aceptable')->nullable();
            $table->string('difisil')->nullable();
            $table->string('conducta')->nullable();
            $table->string('comentario')->nullable();
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
        Schema::dropIfExists('perfil_transacional');
    }
}
