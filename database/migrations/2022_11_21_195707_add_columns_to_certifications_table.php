<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certifications', function (Blueprint $table) {
            $table->foreignId('professional_family_id')->index()->nullable();
            $table->foreignId('professional_area_id')->index()->nullable();
            $table->tinyInteger('level')->nullable();
            $table->string('code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certifications', function (Blueprint $table) {
            $table->dropColumn('professional_family_id');
            $table->dropColumn('professional_area_id');
            $table->dropColumn('level');
            $table->dropColumn('code');
        });
    }
}
