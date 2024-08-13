<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemittedToBillingsTable extends Migration
{
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->tinyInteger('remitted')->default(0)->after('collaborator_id');
        });
    }

    public function down()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn('remitted');
        });
    }
}
