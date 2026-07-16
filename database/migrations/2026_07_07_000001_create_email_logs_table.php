<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('mail_type')->nullable();
            $table->string('mailable')->nullable();
            $table->string('subject')->nullable();
            $table->text('original_to')->nullable();
            $table->text('final_to')->nullable();
            $table->string('status', 20);
            $table->text('error_message')->nullable();
            $table->boolean('is_dry_run')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('tracing_id')->nullable()->constrained('tracings')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('main_company_id')->nullable()->constrained('main_companies')->nullOnDelete();
            $table->timestamps();
            $table->index(['status', 'created_at']);
            $table->index(['mail_type', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
};
