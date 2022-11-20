<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTrainingContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_contracts', function (Blueprint $table) {
            $table->double('percentage_first_year')->default(35);
            $table->double('percentage_second_year')->default(15);
            $table->double('formative_hours_first_year')->default(0);
            $table->double('formative_hours_second_year')->default(0);
            $table->foreignId('provider_id')->index()->nullable();
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
            $table->dropColumn('percentage_first_year');
            $table->dropColumn('percentage_second_year');
            $table->dropColumn('formative_hours_first_year');
            $table->dropColumn('formative_hours_second_year');
            $table->dropColumn('provider_id');
        });
    }
}
