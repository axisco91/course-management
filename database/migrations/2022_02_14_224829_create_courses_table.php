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
            $table->foreignId('course_type_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('nebrija');
            $table->date('beginning')->nullable();
            $table->date('end')->nullable();
            $table->string('morning_schedule')->nullable();
            $table->string('afternoon_schedule')->nullable();
            $table->string('monday')->default(0);
            $table->string('tuesday')->default(0);
            $table->string('wednesday')->default(0);
            $table->string('thursday')->default(0);
            $table->string('friday')->default(0);
            $table->string('saturday')->default(0);
            $table->string('sunday')->default(0);
            $table->foreignId('formation_center_id')->nullable()->references('id')->on('centers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('delivery_center_id')->nullable()->references('id')->on('centers')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('outsourced');
            $table->text('course_observation')->nullable();
            $table->tinyInteger('reactivated')->nullable();
            $table->date('welcome_date')->nullable();
            $table->date('quarter_date')->nullable();
            $table->date('half_date')->nullable();
            $table->date('three_quarters_date')->nullable();
            $table->date('final_date')->nullable();
            $table->foreignId('course_status_id')->nullable()->references('id')->on('course_statuses')->onUpdate('cascade')->onDelete('cascade');
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
