<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormativeActionIdToExamsTutorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams_tutorials', function (Blueprint $table) {
            $table->foreignId('training_action_id')
                ->index()
                ->nullable()
                ->onUpdate('cascade')
                ->onDelete('setNull');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams_tutorials', function (Blueprint $table) {
            $table->dropColumn('training_action_id');
        });
    }
}
