<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('web_platforms', function (Blueprint $table) {
            $table->json('required_moodle_roles')->nullable()->after('required_moodle_usernames');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->json('required_moodle_roles')->nullable()->after('required_moodle_usernames');
        });

        DB::table('courses')->update(['required_moodle_roles' => json_encode([])]);
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('required_moodle_roles');
        });

        Schema::table('web_platforms', function (Blueprint $table) {
            $table->dropColumn('required_moodle_roles');
        });
    }
};
