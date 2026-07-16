<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_company_id')->constrained('main_companies')->cascadeOnDelete();
            $table->string('mail_type', 50);
            $table->string('subject');
            $table->longText('body_html');
            $table->timestamps();

            $table->unique(['main_company_id', 'mail_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_templates');
    }
};
