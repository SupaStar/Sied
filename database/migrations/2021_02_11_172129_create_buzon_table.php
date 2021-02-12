<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuzonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buzon', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("usuario_id");
            $table->foreign("usuario_id")->references("id")->on("users");
            $table->text("titulo");
            $table->text("descripcion");
            $table->integer("estatus")->default(1);
            $table->text("prioridad");
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
        Schema::dropIfExists('buzon');
    }
}
