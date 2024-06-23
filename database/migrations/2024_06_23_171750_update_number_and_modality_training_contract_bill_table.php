<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNumberAndModalityTrainingContractBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_contract_bills', function (Blueprint $table) {
            // Hacer que la columna 'number' sea nullable
            $table->integer('number')->nullable()->change();
            
            // Hacer que la columna 'modality' sea nullable
            $table->string('modality')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_contract_bills', function (Blueprint $table) {
            // Revertir el cambio y hacer que la columna 'number' no sea nullable
            $table->integer('number')->nullable(false)->change();
            
            // Revertir el cambio y hacer que la columna 'modality' no sea nullable
            $table->string('modality')->nullable(false)->change();
        });
    }
}
