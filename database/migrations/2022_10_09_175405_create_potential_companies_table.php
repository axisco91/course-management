<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotentialCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potential_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nif')->nullable();
            $table->foreignId('company_type_id')->constrained();
            $table->foreignId('company_activity_id')->constrained();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('legal_representative')->nullable();
            $table->string('dni_legal_representative')->nullable();
            $table->string('quote')->nullable();
            $table->foreignId('cnae_id')->nullable()->constrained('cnaes');
            $table->string('average_template')->nullable();
            $table->string('iban')->nullable();
            $table->string('sepa')->nullable();
            $table->string('b2b')->nullable();
            $table->string('address')->nullable();
            $table->string('post_code')->nullable();
            $table->foreignId('population_id')->nullable()->constrained();
            $table->foreignId('province_id')->constrained();
            $table->string('population')->nullable();
            $table->string('available_credit')->default(0);
            $table->string('consumed_credit')->default(0);
            $table->string('remaining_credit')->nullable();
            $table->foreignId('advisor_id')->nullable()->constrained();
            $table->foreignId('collaborator_id')->index()->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('potential_companies');
    }
}
