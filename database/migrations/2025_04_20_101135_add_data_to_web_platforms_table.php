<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToWebPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('web_platforms', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('codigo');
            $table->text('token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('web_platforms', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
}
