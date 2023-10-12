<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToChoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chores', function (Blueprint $table) {
            $table->tinyInteger('send_doc_status')->default(0);
            $table->date('send_doc_date')->nullable();
            $table->tinyInteger('tutor_guide_status')->default(0);
            $table->date('tutor_guide_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chores', function (Blueprint $table) {
            $table->dropColumn('send_doc_status');
            $table->dropColumn('send_doc_date');
            $table->dropColumn('tutor_guide_status');
            $table->dropColumn('tutor_guide_date');
        });
    }
}
