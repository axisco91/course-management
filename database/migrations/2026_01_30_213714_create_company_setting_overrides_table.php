<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySettingOverridesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_setting_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_company_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('setting_id')
                ->constrained('setting_definitions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('value')->nullable();
            $table->timestamps();

            // 1 override por empresa y setting
            $table->unique(['main_company_id', 'setting_id']);

            // Índices para rendimiento
            $table->index('main_company_id');
            $table->index('setting_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_setting_overrides');
    }
}
