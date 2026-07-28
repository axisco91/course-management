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
            $table->string('moodle_mode', 20)->default('disabled')->after('web_platform_id');
            $table->unsignedBigInteger('moodle_course_id')->nullable()->after('moodle_mode');
            $table->string('moodle_shortname')->nullable()->after('moodle_course_id');
            $table->string('moodle_sync_status', 20)->default('disconnected')->after('moodle_shortname');
            $table->text('moodle_sync_error')->nullable()->after('moodle_sync_status');
            $table->timestamp('moodle_synced_at')->nullable()->after('moodle_sync_error');
            $table->index(['web_platform_id', 'moodle_course_id']);
            $table->index(['moodle_mode', 'moodle_sync_status']);
        });

        DB::table('courses')->whereNotNull('web_platform_id')->update([
            'moodle_mode' => 'manual',
            'moodle_sync_status' => 'synced',
        ]);

        // Before Moodle provisioning existed, courses inherited Moodle from
        // their training action. Keep those existing links operational.
        DB::table('courses')
            ->whereNull('web_platform_id')
            ->whereIn('training_action_id', function ($query) {
                $query->select('id')
                    ->from('training_actions')
                    ->whereNotNull('web_platform_id');
            })
            ->update([
                'moodle_mode' => 'manual',
                'moodle_sync_status' => 'synced',
            ]);

        Schema::create('moodle_course_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_action_id')->constrained('training_actions')->cascadeOnDelete();
            $table->foreignId('web_platform_id')->constrained('web_platforms')->cascadeOnDelete();
            $table->unsignedBigInteger('moodle_course_id');
            $table->string('moodle_shortname');
            $table->string('moodle_fullname');
            $table->timestamps();
            $table->unique(['training_action_id', 'web_platform_id'], 'moodle_template_action_platform_uq');
        });

        Schema::create('moodle_sync_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('operation', 30);
            $table->string('status', 20)->default('pending');
            $table->string('stage', 50)->nullable();
            $table->unsignedInteger('attempt')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
            $table->index(['course_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('moodle_sync_runs');
        Schema::dropIfExists('moodle_course_templates');
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['web_platform_id', 'moodle_course_id']);
            $table->dropIndex(['moodle_mode', 'moodle_sync_status']);
            $table->dropColumn([
                'moodle_mode', 'moodle_course_id', 'moodle_shortname', 'moodle_sync_status',
                'moodle_sync_error', 'moodle_synced_at',
            ]);
        });
    }
};
