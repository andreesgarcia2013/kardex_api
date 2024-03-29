<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kardex', function (Blueprint $table) {
            $table->id('id_kardex');
            $table->unsignedBigInteger('id_alumno');
            $table->foreign('id_alumno')->references('id_alumno')->on('alumnos');
            $table->unsignedBigInteger('id_materia');
            $table->foreign('id_materia')->references('id_materia')->on('materias');
            $table->integer('calificacion')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('kardex');
    }
};
