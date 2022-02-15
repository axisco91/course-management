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
            $table->string('level_of_studies')->nullable();
            $table->tinyInteger('disabled');
            $table->tinyInteger('social_security_number')->nullable();
            $table->string('c_quote')->nullable();
            $table->tinyInteger('quote_group')->nullable();
            $table->foreignId('professional_category_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('annual_gross_salary')->nullable();
            $table->integer('annual_hours')->nullable();
            $table->string('hourly_cost_worker_gross')->nullable();
            $table->string('direction')->nullable();
            $table->string('post_code')->nullable();
            $table->foreignId('population_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('province_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('observation')->nullable();
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
