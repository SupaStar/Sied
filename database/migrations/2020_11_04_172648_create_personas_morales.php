<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasMorales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas_morales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('morales_id');
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('o_lastname')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_birth')->nullable();
            $table->string('country_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->text('place_birth')->nullable();
            $table->string('job')->nullable();
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
        Schema::dropIfExists('personas_morales');
    }
}
