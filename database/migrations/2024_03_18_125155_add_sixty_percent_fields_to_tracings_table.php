<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSixtyPercentFieldsToTracingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracings', function (Blueprint $table) {
            $table->tinyInteger('sixty_percent_message')->after('half_message')->nullable();
            $table->date('sixty_percent_date_sent')->after('half_date_sent')->nullable();
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
            $table->dropColumn('sixty_percent_message');
            $table->dropColumn('sixty_percent_message_sent');
        });
    }
}