<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingContractSeriesTable extends Migration
{
    public function up()
    {
        Schema::create('training_contract_series', function (Blueprint $table) {
            $table->id();
            $table->string('series');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('training_contract_series');
    }
}