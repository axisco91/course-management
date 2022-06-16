<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuoteGroupIdToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('quote_group_id')->nullable();
            $table->foreign('quote_group_id')->references('id')->on('quote_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->dropColumn('quote_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['quote_group_id']);
            $table->dropColumn('quote_group_id');
            $table->Integer('quote_group')->nullable();
        });
    }
}
