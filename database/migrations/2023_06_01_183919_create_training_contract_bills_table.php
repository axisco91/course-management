<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingContractBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_contract_bills', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->foreignId('training_contract_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('training_contract_bonus_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->tinyInteger('series')->default(3);
            $table->date('collection_date')->nullable();
            $table->tinyInteger('month');
            $table->integer('year');
            $table->double('amount')->nullable();
            $table->tinyInteger('modalities');
            $table->tinyInteger('hours')->nullable();
            $table->tinyInteger('price_hours')->default(5);
            $table->tinyInteger('charged')->default(0);
            $table->tinyInteger('invoiced')->default(0);
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
        Schema::dropIfExists('training_contract_bills');
    }
}
