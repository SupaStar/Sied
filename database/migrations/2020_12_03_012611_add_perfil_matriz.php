<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPerfilMatriz extends Migration
{
    public function up()
    {
        Schema::table('perfil_transacional', function (Blueprint $table) {
            $table->bigInteger('origen_recursos')->nullable();
            $table->bigInteger('destino_recursos')->nullable();
            $table->bigInteger('instrumento_monetario')->nullable();
            $table->bigInteger('divisa')->nullable();
        });
    }
  
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perfil_transacional', function (Blueprint $table) {
            $table->dropColumn('origen_recursos');
            $table->dropColumn('destino_recursos');
            $table->dropColumn('instrumento_monetario');
            $table->dropColumn('divisa');
        });
    }
  }
