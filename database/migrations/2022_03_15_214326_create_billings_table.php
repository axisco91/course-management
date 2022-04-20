<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('is_bonus')->default(0);
            $table->tinyInteger('number_students')->default(0);
            $table->decimal('billing')->default(0);
            $table->decimal('bonus')->default(0);
            $table->decimal('total_training_activity')->default(0);
            $table->decimal('expenses')->default(0);
            $table->decimal('only_organizing_entity')->default(0);
            $table->decimal('salary_costs')->default(0);
            $table->foreignId('payment_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('communication_start_date')->nullable();
            $table->date('comunication_end_date')->nullable();
            $table->tinyInteger('invoiced')->default(0);
            $table->string('billing_number')->nullable()->unique();
            $table->date('billing_date')->nullable();
            $table->date('collection_date')->nullable();
            $table->tinyInteger('bonus_status')->default(0);
            $table->tinyInteger('company_bonus')->default(0);
            $table->text('observation')->nullable();
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
        Schema::dropIfExists('billings');
    }
}
