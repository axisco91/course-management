<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressToTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('direction')->nullable();
            $table->string('post_code')->nullable();
            $table->foreignId('province_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->text('population')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropColumn('province_id');
            $table->dropColumn('direction');
            $table->dropColumn('post_code');
            $table->dropColumn('population');
        });
    }
}
