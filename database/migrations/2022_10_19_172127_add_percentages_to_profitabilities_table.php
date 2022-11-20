<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPercentagesToProfitabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profitabilities', function (Blueprint $table) {
            $table->float('advisor_percentage')->nullable();
            $table->float('collaborator_percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profitabilities', function (Blueprint $table) {
            $table->dropColumn('advisor_percentage');
            $table->dropColumn('collaborator_percentage');
        });
    }
}
