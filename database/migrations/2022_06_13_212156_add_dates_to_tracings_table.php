<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesToTracingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracings', function (Blueprint $table) {
            $table->date('welcome_date_sent')->nullable();
            $table->date('quarter_date_sent')->nullable();
            $table->date('half_date_sent')->nullable();
            $table->date('three_quarters_date_sent')->nullable();
            $table->date('final_date_sent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracings', function (Blueprint $table) {
            $table->dropColumn('welcome_date_sent');
            $table->dropColumn('quarter_date_sent');
            $table->dropColumn('half_date_sent');
            $table->dropColumn('three_quarters_date_sent');
            $table->dropColumn('final_date_sent');
        });
    }
}
