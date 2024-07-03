<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBonificationToTrainingContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_contracts', function (Blueprint $table) {
            $table->boolean('bonification')->default(false)->after('daily_hours_2');
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
            $table->dropColumn('bonification');
        });
    }
}
