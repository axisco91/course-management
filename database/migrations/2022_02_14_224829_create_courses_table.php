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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('training_action_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('group');
            $table->foreignId('company_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('course_type_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('price');
            $table->tinyInteger('nebrija');
            $table->date('beginning')->nullable();
            $table->date('end')->nullable();
            $table->string('morning_schedule')->nullable();
            $table->string('afternoon_schedule')->nullable();
            $table->string('teaching_days')->nullable();
            $table->string('formation_center')->nullable();
            $table->string('delivery_center')->nullable();
            $table->tinyInteger('outsourced');
            $table->string('course_observation')->nullable();
            $table->string('company_observation')->nullable();
            $table->tinyInteger('reactivated')->nullable();
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
        Schema::dropIfExists('courses');
    }
};
