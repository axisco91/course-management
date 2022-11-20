<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingContractIncidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_contract_incidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incidence_type_id')->index();
            $table->foreignId('user_id')->index();
            $table->foreignId('training_contract_id')->index();
            $table->string('affair');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_contract_incidences');
    }
}
