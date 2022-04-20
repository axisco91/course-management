<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('course_status_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('number_students')->default(0);
            $table->integer('billing')->default(0);
            $table->integer('bonus')->default(0);
            $table->integer('total_training_activity')->default(0);
            $table->integer('organization_expenses')->default(0);
            $table->integer('only_organizing_entity')->default(0);
            $table->integer('average_template')->default(0);
            $table->integer('salary_cost')->default(0);
            $table->foreignId('payment_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('start_communication_date')->nullable();
            $table->date('close_communication_date')->nullable();
            $table->tinyInteger('invoiced')->default(0);
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('collection_date')->nullable();
            $table->tinyInteger('status_bonus')->default(0);
            $table->date('date')->nullable();
            $table->tinyInteger('company_bonus')->default(0);
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
        Schema::dropIfExists('bonuses');
    }
}
