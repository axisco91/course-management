<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddUuidToMainCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_companies', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        $companies = DB::table('main_companies')->select('id')->get();
        foreach ($companies as $company) {
            DB::table('main_companies')
                ->where('id', $company->id)
                ->update(['uuid' => (string) Str::uuid()]);
        }

        Schema::table('main_companies', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_companies', function (Blueprint $table) {
            $table->dropUnique('main_companies_uuid_unique');
            $table->dropColumn('uuid');
        });
    }
}
