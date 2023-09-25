<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommunityIdToTrainingContractFestivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_contract_festivals', function (Blueprint $table) {
            $table->foreignId('community_festival_id')->index()->nullable()->onUpdate('cascade')->onDelete('setNull');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_contract_festivals', function (Blueprint $table) {
            $table->dropColumn('community_festival_id');
        });
    }
}
