<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseOriginIdToTrainingActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_actions', function (Blueprint $table) {
            $table->foreignId('course_origin_id')->index()->nullable()->onUpdate('cascade')->onDelete('setNull');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_actions', function (Blueprint $table) {
            $table->dropColumn('course_origin_id');
        });
    }
}
