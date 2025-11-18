<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseTypeIdToCommissionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_types', function (Blueprint $table) {
            $table->foreignId('course_types_id')
                ->index()
                ->nullable()
                ->onUpdate('cascade')
                ->onDelete('setNull');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_types', function (Blueprint $table) {
            $table->dropColumn('course_types_id');
        });
    }
}
