<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('moodle_category_id')->nullable()->after('moodle_course_id');
            $table->unsignedTinyInteger('moodle_provisioning_version')->default(2)->after('moodle_category_id');
        });

        DB::table('courses')->update(['moodle_provisioning_version' => 1]);
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['moodle_category_id', 'moodle_provisioning_version']);
        });
    }
};
