<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('web_platform_email_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('web_platform_id')->constrained('web_platforms')->cascadeOnDelete();
            $table->string('mail_type', 50);
            $table->string('subject');
            $table->longText('body_html');
            $table->timestamps();
            $table->unique(['web_platform_id', 'mail_type']);
        });
    }

    public function down() { Schema::dropIfExists('web_platform_email_templates'); }
};
