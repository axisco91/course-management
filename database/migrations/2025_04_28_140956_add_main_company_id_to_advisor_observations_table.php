<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainCompanyIdToAdvisorObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advisor_observations', function (Blueprint $table) {
            $table->foreignId('main_company_id')
                ->index()
                ->nullable()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advisor_observations', function (Blueprint $table) {
            //
        });
    }
}
