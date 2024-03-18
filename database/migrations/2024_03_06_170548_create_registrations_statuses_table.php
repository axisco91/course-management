<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationsStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    
        Schema::table('registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->after('billing_id')->nullable();
            $table->date('on_leave_date')->nullable();
            $table->foreign('status_id')->references('id')->on('registrationsStatuses'); // Cambia 'statuses' a 'registrationsStatuses'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrations_statuses');
    }
}
