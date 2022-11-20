<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('formative_module')->nullable();
            $table->string('name');
            $table->integer('face_to_face_hours')->default(0);
            $table->integer('tutoring_hours')->default(0);
            $table->integer('exam_hours')->default(0);
            $table->integer('teletraining_hours')->default(0);
            $table->integer('total_hours')->default(0);
            $table->tinyInteger('active');
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
        Schema::dropIfExists('modules');
    }
}
