<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyIncidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_incidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incidence_type_id')->index();
            $table->foreignId('user_id')->index();
            $table->foreignId('company_id')->index();
            $table->string('affair');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('company_incidences');
    }
}
