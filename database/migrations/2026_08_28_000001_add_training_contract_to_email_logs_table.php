<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->foreignId('training_contract_id')
                ->nullable()
                ->after('student_id')
                ->constrained('training_contracts')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('training_contract_id');
        });
    }
};
