<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->string('channel', 40)->default('smtp')->after('mailable');
            $table->unsignedBigInteger('moodle_message_id')->nullable()->after('sent_at');
            $table->string('idempotency_key', 64)->nullable()->unique()->after('moodle_message_id');
        });
    }

    public function down()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropUnique(['idempotency_key']);
            $table->dropColumn(['channel', 'moodle_message_id', 'idempotency_key']);
        });
    }
};
