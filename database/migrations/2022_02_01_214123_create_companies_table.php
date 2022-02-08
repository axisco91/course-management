<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('buisness_name');
            $table->string('nif');
            $table->foreignId('type_id')->constrained('company_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('activities_id')->constrained('company_activities')->onUpdate('cascade')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('legal_representative')->nullable();
            $table->string('dni_legal_representative')->nullable();
            $table->string('quote')->nullable();
            $table->foreignId('cnae_id')->constrained('cnaes')->onUpdate('cascade')->onDelete('cascade');
            $table->string('average_template')->nullable();
            $table->string('iban')->nullable();
            $table->string('sepa')->nullable();
            $table->string('b2b')->nullable();
            $table->string('direction')->nullable();
            $table->string('post_code')->nullable();
            $table->foreignId('population_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('province_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
