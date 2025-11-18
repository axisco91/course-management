<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollaboratorIdToLiquidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquidations', function (Blueprint $table) {
            $table->foreignId('collaborator_id')
                ->index()
                ->nullable()
                ->onUpdate('cascade')
                ->onDelete('setNull');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liquidations', function (Blueprint $table) {
            $table->dropColumn('collaborator_id');
        });
    }
}
