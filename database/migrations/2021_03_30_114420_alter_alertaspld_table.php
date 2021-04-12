<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAlertaspldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('alertas_pld', function (Blueprint $table) {
        $table->text('titulo');
        /*$table->text('descripcion');
        $table->integer('estatus');
        $table->text('observacion')->nullable();
        $table->text('prioridad');*/
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
