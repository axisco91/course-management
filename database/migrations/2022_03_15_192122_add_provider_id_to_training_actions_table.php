<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProviderIdToTrainingActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_actions', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('providers')->onUpdate('cascade')->onDelete('cascade');
            $table->dropForeign(['course_provider_id']);
            $table->dropColumn('course_provider_id');
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
            $table->dropForeign(['provider_id']);
            $table->dropColumn('provider_id');
            $table->unsignedBigInteger('course_provider_id')->nullable();
            $table->foreign('course_provider_id')->references('id')->on('course_providers')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
