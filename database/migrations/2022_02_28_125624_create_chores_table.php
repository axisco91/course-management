<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('membership_tab_status')->default(0);
            $table->date('membership_tab_date')->nullable();
            $table->tinyInteger('economic_proposal_status')->default(0);
            $table->date('economic_proposal_date')->nullable();
            $table->tinyInteger('student_tab_status')->default(0);
            $table->date('student_tab_date')->nullable();
            $table->tinyInteger('welcome_guid_status')->default(0);
            $table->date('welcome_guid_date')->nullable();
            $table->tinyInteger('registration_status')->default(0);
            $table->date('registration_date')->nullable();
            $table->tinyInteger('diploma_status')->default(0);
            $table->date('diploma_status_date')->nullable();
            $table->tinyInteger('start_communication_status')->default(0);
            $table->date('start_communication_date')->nullable();
            $table->tinyInteger('close_communication_status')->default(0);
            $table->date('close_communication_date')->nullable();
            $table->tinyInteger('invoiced_status')->default(0);
            $table->date('invoiced_date')->nullable();
            $table->tinyInteger('bonus_sent_status')->default(0);
            $table->date('bonus_sent_date')->nullable();
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
        Schema::dropIfExists('chores');
    }
}
