<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_actions', function (Blueprint $table) {
            $table->id();
            $table->string('accion_formative');
            $table->string('name');
            $table->foreignId('teacher_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('provider_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('course_z');
            $table->tinyInteger('course_avz');
            $table->tinyInteger('active');
            $table->tinyInteger('in_catalog')->nullable();
            $table->integer('face_to_face_hours')->default(0);
            $table->integer('teletraining_hours')->default(0);
            $table->integer('total_hours')->default(0);
            $table->decimal('price')->default('0.00');
            $table->text('objectives')->nullable();
            $table->text('content')->nullable();
            $table->string('user')->nullable();
            $table->string('password')->nullable();
            $table->string('web_platform')->nullable();
            $table->string('observations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_actions');
    }
}
