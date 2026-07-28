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
            $table->json('required_moodle_usernames')->nullable()->after('token');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->json('required_moodle_usernames')->nullable()->after('moodle_shortname');
        });

        // Existing courses must never receive these users automatically.
        DB::table('courses')->update(['required_moodle_usernames' => json_encode([])]);
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('required_moodle_usernames');
        });

        Schema::table('web_platforms', function (Blueprint $table) {
            $table->dropColumn('required_moodle_usernames');
        });
    }
};
