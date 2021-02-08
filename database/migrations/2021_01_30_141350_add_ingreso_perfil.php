<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIngresoPerfil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perfil_transacional', function (Blueprint $table) {
          $table->decimal('ingreso',10,2)->nullable();
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
          $table->dropColumn('ingreso');
        });
    }
}
