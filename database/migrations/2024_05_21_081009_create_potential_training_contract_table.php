<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotentialTrainingContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potential_training_contract', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('company_tutor');
            $table->string('company_tutor_dni');
            $table->string('workplace');
            $table->string('advisory');
            $table->string('province');
            $table->string('student');
            $table->string('occupation');
            $table->boolean('disabled');
            $table->boolean('youth_guarantee');
            $table->boolean('social_exclusion');
            $table->string('specialty');
            $table->string('professional_certificate');
            $table->date('contract_start_date');
            $table->date('training_start_date');
            $table->integer('total_contract_hours');
            $table->string('training_schedule');
            $table->string('working_schedule');
            $table->string('full_schedule');
            $table->boolean('monday');
            $table->boolean('tuesday');
            $table->boolean('wednesday');
            $table->boolean('thursday');
            $table->boolean('friday');
            $table->boolean('saturday');
            $table->boolean('sunday');
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
        Schema::dropIfExists('potential_training_contract');
    }
}
