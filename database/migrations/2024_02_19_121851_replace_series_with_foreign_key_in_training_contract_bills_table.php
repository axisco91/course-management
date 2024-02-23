<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceSeriesWithForeignKeyInTrainingContractBillsTable extends Migration
{
    public function up()
    {
        Schema::table('training_contract_bills', function (Blueprint $table) {
            // Primero, elimina la columna 'series' existente
            $table->dropColumn('series');

            // Luego, agrega la nueva columna 'series_id' que será una clave foránea
            $table->unsignedBigInteger('series_id')->nullable();

            $table->foreign('series_id')
                  ->references('id')
                  ->on('training_contract_series')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('training_contract_bills', function (Blueprint $table) {
            // Al revertir, primero elimina la clave foránea 'series_id'
            $table->dropForeign(['series_id']);
            $table->dropColumn('series_id');

            // Luego, agrega de nuevo la columna 'series'
            $table->string('series')->nullable();
        });
    }
}