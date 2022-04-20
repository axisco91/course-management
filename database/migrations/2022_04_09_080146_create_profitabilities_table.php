<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfitabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profitabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('price')->default(0.00);
            $table->tinyInteger('license')->default(0);
            $table->decimal('teacher')->default(0.00);
            $table->decimal('management')->default(0.00);
            $table->decimal('nebrija_title')->default(0.00);
            $table->decimal('discount')->default(0.00);
            $table->decimal('collaborator_commission')->default(0.00);
            $table->decimal('advisor_commission')->default(0.00);
            $table->decimal('total')->default(0.00);
            $table->decimal('benefits')->default(0.00);
            $table->text('observations')->nullable();
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
        Schema::dropIfExists('profitabilities');
    }
}
