<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopulationFestivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('population_festivals', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->string('name')->nullable();
            $table->foreignId('population_id')->index()->nullable()->onUpdate('cascade')->onDelete('setNull');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('population_festivals');
    }
}
