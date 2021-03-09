<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCamposMorales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('morales', function (Blueprint $table) {
        $table->string("giro",50);
        $table->string("nombre_administrador");
        $table->date("fecha_constitucion")->default(\Carbon\Carbon::now());
        $table->text("garantias");
        $table->float("lat",20,14);
        $table->float("long",20,14);
        $table->integer("numero_empleados");
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
