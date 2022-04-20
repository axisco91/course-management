<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTeacherAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas_teacher_areas', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('teacher_area_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas_teacher_areas');
    }
}
