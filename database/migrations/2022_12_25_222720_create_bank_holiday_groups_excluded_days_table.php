<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankHolidayGroupsExcludedDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_holiday_groups_excluded_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('excluded_day_id');
            $table->foreignId('group_id')->nullable()->references('id')->on('bank_holiday_groups')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_holiday_groups_excluded_days');
    }
}
