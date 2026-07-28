<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracings', function (Blueprint $table) {
            $table->dropColumn('one_week_message');
            $table->dropColumn('one_week_date_sent');
        });
    }
};
