<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTutorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams_tutorials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_contract_id')->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('center_id')->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('type');
            $table->date('date');
            $table->time('beginning');
            $table->time('end');
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
        Schema::dropIfExists('exams_tutorials');
    }
}
