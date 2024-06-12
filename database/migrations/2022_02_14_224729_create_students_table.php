<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('dni');
            $table->string('telephone');
            $table->string('email');
            $table->foreignId('company_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('user');
            $table->string('password');
            $table->date('date_of_birth')->nullable();
            $table->foreignId('level_study_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('disabled')->default(0);
            $table->string('social_security_number')->nullable();
            $table->string('c_quote')->nullable();
            $table->Integer('quote_group')->nullable();
            $table->foreignId('professional_category_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('annual_gross_salary')->nullable();
            $table->integer('annual_hours')->nullable();
            $table->string('hourly_cost_worker_gross')->nullable();
            $table->string('direction')->nullable();
            $table->string('post_code')->nullable();
            $table->foreignId('population_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('province_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->text('population')->nullable();
            $table->text('observation')->nullable();
            $table->string('iban')->nullable();
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
        Schema::dropIfExists('students');
    }
};
