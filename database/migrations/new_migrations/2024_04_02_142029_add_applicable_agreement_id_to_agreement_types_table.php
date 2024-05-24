<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApplicableAgreementIdToAgreementTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agreement_types', function (Blueprint $table) {
            $table->unsignedBigInteger('applicable_agreement_id')->nullable();

            $table->foreign('applicable_agreement_id')->references('id')->on('applicable_agreements');
        });
    }

    public function down()
    {
        Schema::table('agreement_types', function (Blueprint $table) {
            $table->dropForeign(['applicable_agreement_id']);
            $table->dropColumn('applicable_agreement_id');
        });
    }
}
