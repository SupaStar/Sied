<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteForeignRiesgos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('riesgos', function (Blueprint $table) {
        $table->dropForeign('riesgos_id_cliente_foreign');
      });
      Schema::table('riesgos', function (Blueprint $table) {
        $table->integer('tipo')->default(0);
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
