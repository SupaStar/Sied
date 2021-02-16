<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterConfiguracionuidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('configuracionalerta', function (Blueprint $table) {
        $table->decimal("valor");
        $ayer = Carbon::now()->addDays(-1)->format("Y-m-d");
        $table->dateTime('actualizacionUid')->default($ayer);
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
