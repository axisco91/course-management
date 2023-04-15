<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracingCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracing_communications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incidence_type_id')->index();
            $table->foreignId('user_id')->index();
            $table->foreignId('tracing_id')->index();
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
        Schema::dropIfExists('tracing_communications');
    }
}
