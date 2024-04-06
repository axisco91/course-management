<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicableAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicable_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->integer('annually_hours');
            $table->unsignedBigInteger('agreement_type_id'); // Futura clave foránea
            $table->timestamps();

             // Definición de la clave foránea (descomenta cuando la tabla agreement_types esté lista)
            // $table->foreign('agreement_type_id')->references('id')->on('agreement_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicable_agreements');
    }
}
