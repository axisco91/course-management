<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->string('nif')->nullable();
            $table->foreignId('company_type_id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('company_activity_id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('legal_representative')->nullable();
            $table->string('dni_legal_representative')->nullable();
            $table->foreignId('cnae_id')->nullable()->constrained('cnaes')->onUpdate('cascade')->onDelete('cascade');
            $table->string('iban')->nullable();
            $table->string('sepa')->nullable();
            $table->string('b2b')->nullable();
            $table->string('address')->nullable();
            $table->string('post_code')->nullable();
            $table->foreignId('population_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('province_id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('population')->nullable();
            $table->tinyInteger('active')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn('nif');
            $table->dropForeign(['company_type_id']);
            $table->dropColumn('company¡_type_id');
            $table->dropForeign(['company_activity_id']);
            $table->dropColumn('company_activity_id');
            $table->dropColumn('email');
            $table->dropColumn('telephone');
            $table->dropColumn('legal_representative');
            $table->dropColumn('dni_legal_representative');
            $table->dropForeign(['cnae_id']);
            $table->dropColumn('cnae_id');
            $table->dropColumn('iban');
            $table->dropColumn('sepa');
            $table->dropColumn('b2b');
            $table->dropColumn('address');
            $table->dropColumn('post_code');
            $table->dropForeign(['population_id']);
            $table->dropColumn('population_id');
            $table->dropForeign(['province_id']);
            $table->dropColumn('province_id');
            $table->dropColumn('population');
            $table->dropColumn('active');
        });
    }
}
