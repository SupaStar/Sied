<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAlertaBuzonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('alertas_pld', function (Blueprint $table) {
        $table->integer("envio");
      });
      Schema::table('configuracionalerta', function (Blueprint $table) {
        $table->decimal("tiie28");
        $table->decimal("fix");
        $table->decimal("cetes28");
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
