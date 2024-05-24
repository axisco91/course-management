<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTutorToTrainingContractElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_contract_elements', function (Blueprint $table) {
            $table->string('training_tutor')->nullable();        
            $table->string('training_tutor_dni')->nullable();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_contract_elements', function (Blueprint $table) {
            $table->dropColumn('training_tutor');
            $table->dropColumn('training_tutor_dni');
        });
    }
}