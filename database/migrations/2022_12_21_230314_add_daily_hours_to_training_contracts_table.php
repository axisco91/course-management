<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyHoursToTrainingContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_contracts', function (Blueprint $table) {
            $table->float('daily_hours')->nullable();
            $table->integer('total_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_contracts', function (Blueprint $table) {
            $table->dropColumn('daily_hours');
            $table->dropColumn('total_days');
        });
    }
}
