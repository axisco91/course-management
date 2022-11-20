<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotentialStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potential_students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('dni');
            $table->string('telephone');
            $table->string('email');
            $table->string('company_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->foreignId('level_study_id')->nullable()->index();
            $table->tinyInteger('disabled')->default(0);
            $table->string('social_security_number')->nullable();
            $table->foreignId('professional_category_id')->nullable()->index();
            $table->string('direction')->nullable();
            $table->string('post_code')->nullable();
            $table->foreignId('population_id')->nullable()->index();
            $table->foreignId('province_id')->nullable()->index();
            $table->text('population')->nullable();
            $table->foreignId('training_action_id')->nullable()->index();
            $table->foreignId('professional_family_id')->nullable()->index();
            $table->foreignId('professional_area_id')->nullable()->index();
            $table->tinyInteger('converted')->default(0);
            $table->foreignId('potential_company_id')->nullable()->index();
            $table->foreignId('company_id')->nullable()->index();
            $table->foreignId('student_id')->nullable()->index();
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
        Schema::dropIfExists('potential_students');
    }
}
