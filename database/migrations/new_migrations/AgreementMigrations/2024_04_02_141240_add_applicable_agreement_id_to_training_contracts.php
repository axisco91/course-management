<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApplicableAgreementIdToTrainingContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('applicable_agreement_id')->nullable();

            $table->foreign('applicable_agreement_id')->references('id')->on('applicable_agreements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_contracts', function (Blueprint $table) {
            $table->dropForeign(['applicable_agreement_id']);
            $table->dropColumn('applicable_agreement_id');
        });
    }
}
