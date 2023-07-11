<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvinceFestivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('province_festivals', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->string('nombre')->nullable();
            $table->foreignId('province_id')->index()->nullable()->onUpdate('cascade')->onDelete('setNull');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('province_festivals');
    }
}
