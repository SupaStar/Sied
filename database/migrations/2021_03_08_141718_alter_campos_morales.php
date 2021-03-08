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
        $table->float("lat");
        $table->float("long");
        $table->integer("numero_empleados");
        $table->string("entrevista",200);
        $table->string("fotografia1",200);
        $table->string("fotografia2",200)->nullable();
        $table->string("reporte",200);
        $table->string("autorizacion_reporte_circulo_credito",200);
        $table->string("ultima_declaracion_anual",200);
        $table->string("estados_financieros_anuales",200);
        $table->string("estados_financieros_recientes",200);
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
