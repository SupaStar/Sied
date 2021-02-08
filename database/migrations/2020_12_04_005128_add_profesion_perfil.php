<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfesionPerfil extends Migration
{
    public function up()
    {
        Schema::table('perfil_transacional', function (Blueprint $table) {
            $table->bigInteger('profesion')->nullable();
            $table->bigInteger('actividad_giro')->nullable();
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
            $table->dropColumn('profesion');
            $table->dropColumn('actividad_giro');
        });
    }
}
