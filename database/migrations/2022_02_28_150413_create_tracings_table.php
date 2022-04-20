<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('performed_activities')->nullable()->default(0);
            $table->tinyInteger('performed_hours')->nullable()->default(0);
            $table->tinyInteger('performed_units')->nullable()->default(0);
            $table->date('follow_up_date')->nullable();
            $table->tinyInteger('final_test')->nullable()->default(0);
            $table->tinyInteger('questionnaire')->nullable()->default(0);
            $table->tinyInteger('welcome_message')->nullable()->default(0);
            $table->tinyInteger('quarter_message')->nullable()->default(0);
            $table->tinyInteger('half_message')->nullable()->default(0);
            $table->tinyInteger('three_quarters_message')->nullable()->default(0);
            $table->tinyInteger('final_message')->nullable()->default(0);
            $table->text('observation')->nullable();
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
        Schema::dropIfExists('tracings');
    }
}
