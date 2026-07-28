<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumns('courses', ['moodle_mode', 'moodle_sync_status'])) {
            return;
        }

        DB::table('courses')
            ->whereNull('web_platform_id')
            ->where('moodle_mode', 'disabled')
            ->where('created_at', '<', '2026-07-21 00:00:00')
            ->whereIn('training_action_id', function ($query) {
                $query->select('id')
                    ->from('training_actions')
                    ->whereNotNull('web_platform_id');
            })
            ->update([
                'moodle_mode' => 'manual',
                'moodle_sync_status' => 'synced',
                'moodle_sync_error' => null,
            ]);
    }

    public function down(): void
    {
        // The previous state cannot be distinguished safely from courses that
        // were intentionally linked manually.
    }
};
