<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_contracts', function (Blueprint $table) {
            $table->string('remuneration')->nullable()->after('center_of_work');
            $table->string('remuneration_period')->nullable()->after('remuneration');
            $table->string('annual_holidays')->nullable()->after('remuneration_period');
        });
    }

    public function down(): void
    {
        Schema::table('training_contracts', function (Blueprint $table) {
            $table->dropColumn(['remuneration', 'remuneration_period', 'annual_holidays']);
        });
    }
};
