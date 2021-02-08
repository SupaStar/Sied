<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprobantesPago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes_pago', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pago_id');
            $table->text('path');
            $table->string('extension');
            $table->text('name');
            $table->text('full');
            $table->integer('user_id');
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
        Schema::dropIfExists('comprobantes_pago');
    }
}
