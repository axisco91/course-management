<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiquidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquidations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('course_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->date('beginning');
            $table->date('end');
            $table->double('price');
            $table->tinyInteger('paid')->default(0);
            $table->double('commission_percent')->nullable();
            $table->double('commission')->nullable();
            $table->date('paid_date')->nullable();
            $table->date('invoice_date')->nullable();
            $table->foreignId('advisor_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('bill_number')->nullable();
            $table->tinyInteger('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liquidations');
    }
}
