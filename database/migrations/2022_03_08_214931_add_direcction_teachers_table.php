<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirecctionTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('social_security_number')->nullable();
            $table->string('address')->nullable();
            $table->string('post_code')->nullable();
            $table->string('population')->nullable();
            $table->foreignId('province_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('iban')->nullable();
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
            $table->dropColumn('social_security_number');
            $table->dropColumn('address');
            $table->dropColumn('post_code');
            $table->dropColumn('population');
            $table->dropColumn('iban');
        });
    }
}
