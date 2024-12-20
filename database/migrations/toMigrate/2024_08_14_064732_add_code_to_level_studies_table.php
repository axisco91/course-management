<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeToLevelStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('level_studies', function (Blueprint $table) {
            $table->string('code', 2)->after('name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('level_studies', function (Blueprint $table) {
            $table->dropColumn('code');
    });
    }
}
