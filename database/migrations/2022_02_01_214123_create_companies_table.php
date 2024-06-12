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
            $table->string('name');
            $table->string('nif')->nullable();
            $table->foreignId('company_type_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('company_activity_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('legal_representative')->nullable();
            $table->string('dni_legal_representative')->nullable();
            $table->string('quote')->nullable();
            $table->foreignId('cnae_id')->nullable()->constrained('cnaes')->onUpdate('cascade')->onDelete('cascade');
            $table->string('average_template')->nullable();
            $table->string('iban')->nullable();
            $table->string('sepa')->nullable();
            $table->string('b2b')->nullable();
            $table->string('address')->nullable();
            $table->string('post_code')->nullable();
            $table->foreignId('population_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('province_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('population')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->string('available_credit')->default(0);
            $table->string('consumed_credit')->default(0);
            $table->string('remaining_credit')->nullable();
            $table->tinyInteger('potential')->default(0);
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
