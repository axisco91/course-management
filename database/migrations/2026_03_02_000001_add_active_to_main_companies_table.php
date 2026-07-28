<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddActiveToMainCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('main_companies', 'active')) {
            Schema::table('main_companies', function (Blueprint $table) {
                $table->unsignedTinyInteger('active')->default(1)->after('logo');
            });
        }

        DB::table('main_companies')
            ->whereNull('active')
            ->update(['active' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_companies', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
