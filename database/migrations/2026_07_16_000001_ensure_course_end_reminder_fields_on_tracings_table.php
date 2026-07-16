<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tracings', 'one_week_message')) {
            Schema::table('tracings', function (Blueprint $table) {
                $table->tinyInteger('one_week_message')->nullable()->default(0)->after('final_message');
            });
        }

        if (!Schema::hasColumn('tracings', 'one_week_date_sent')) {
            Schema::table('tracings', function (Blueprint $table) {
                $table->date('one_week_date_sent')->nullable()->after('final_date_sent');
            });
        }
    }

    public function down(): void
    {
        // Repair migration: keep existing production data on rollback.
    }
};
