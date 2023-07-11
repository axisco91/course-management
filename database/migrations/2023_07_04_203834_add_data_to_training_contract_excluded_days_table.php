<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToTrainingContractExcludedDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_contracts_excluded_days', function (Blueprint $table) {
            $table->date('day');
            $table->foreignId('excluded_day_type_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->dropColumn('excluded_day_id');
            $table->text('description')->nullable();
            $table->integer('group')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_contracts_excluded_days', function (Blueprint $table) {
            $table->dropColumn('day');
            $table->dropForeign(['excluded_day_type_id']);
            $table->dropColumn('excluded_day_type_id');
            $table->foreignId('excluded_day_id');
            $table->dropColumn('description');
            $table->dropColumn('group');
        });
    }
}
