<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('number_cfa')->nullable();
            $table->foreignId('company_id');
            $table->foreignId('student_id');
            $table->string('company_tutor')->nullable();
            $table->string('company_tutor_dni')->nullable();
            $table->foreignId('occupation_id')->nullable();
            $table->string('center_of_work')->nullable();
            $table->foreignId('province_id')->nullable();
            $table->tinyInteger('disabled')->default(0);
            $table->tinyInteger('youth_guarantee')->default(0);
            $table->tinyInteger('social_exclusion')->default(0);
            $table->tinyInteger('specialty')->default(0);
            $table->tinyInteger('professional_certificate')->default(0);
            $table->date('beginning');
            $table->date('end');
            $table->date('beginning_formation');
            $table->date('end_formation')->nullable();
            $table->integer('formation_hours')->nullable();
            $table->integer('annually_day_hours')->nullable();
            $table->integer('bonus_hours_first_year')->nullable();
            $table->integer('bonus_hours_second_year')->nullable();
            $table->string('monday')->default(0);
            $table->string('tuesday')->default(0);
            $table->string('wednesday')->default(0);
            $table->string('thursday')->default(0);
            $table->string('friday')->default(0);
            $table->string('saturday')->default(0);
            $table->string('sunday')->default(0);
            $table->string('training_schedule')->nullable();
            $table->string('working_hours')->nullable();
            $table->string('complete_schedule')->nullable();
            $table->foreignId('training_contract_status_id')->index()->nullable();
            $table->foreignId('on_leave_type_id')->index()->nullable();
            $table->date('on_leave_date')->nullable();
            $table->foreignId('advisor_id')->index()->nullable();
            $table->foreignId('collaborator_id')->index()->nullable()->references('id')->on('users');
            $table->integer('total_hours')->default(0);
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
        Schema::dropIfExists('training_contracts');
    }
}
