<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingContractFestivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_contract_festivals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_contract_id')->index()->nullable()->onUpdate('cascade')->onDelete('setNull');
            $table->foreignId('nacional_festival_id')->index()->nullable()->onUpdate('cascade')->onDelete('setNull');
            $table->foreignId('province_festival_id')->index()->nullable()->onUpdate('cascade')->onDelete('setNull');
            $table->foreignId('population_festival_id')->index()->nullable()->onUpdate('cascade')->onDelete('setNull');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_contract_festivals');
    }
}
