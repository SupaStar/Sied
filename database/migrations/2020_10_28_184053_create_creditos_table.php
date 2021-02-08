<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->string('nombre', 300);
            $table->bigInteger('monto');
            $table->string('tipo', 300);
            $table->string('pagos', 300);
            $table->integer('numero_plazo');
            $table->string('unidad_plazo');
            $table->string('status')->default('activo');
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('grupo_id')->references('id')->on('grupos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creditos');
    }
}
