<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCorrectionToDaysToTrainingContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_contracts', function (Blueprint $table) {
            $table->boolean('monday')->default(0)->change();
            $table->boolean('tuesday')->default(0)->change();
            $table->boolean('wednesday')->default(0)->change();
            $table->boolean('thursday')->default(0)->change();
            $table->boolean('friday')->default(0)->change();
            $table->boolean('saturday')->default(0)->change();
            $table->boolean('sunday')->default(0)->change();
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
            $table->string('monday')->default('0')->change();
            $table->string('tuesday')->default('0')->change();
            $table->string('wednesday')->default('0')->change();
            $table->string('thursday')->default('0')->change();
            $table->string('friday')->default('0')->change();
            $table->string('saturday')->default('0')->change();
            $table->string('sunday')->default('0')->change();
        });
    }
}
