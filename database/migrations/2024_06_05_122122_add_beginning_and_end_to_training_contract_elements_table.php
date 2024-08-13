<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBeginningAndEndToTrainingContractElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_contract_elements', function (Blueprint $table) {
            $table->date('beginning')->nullable()->after('total_days');
            $table->date('end')->nullable()->after('beginning');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_contract_elements', function (Blueprint $table) {
            $table->dropColumn('beginning');
            $table->dropColumn('end');
        });
    }
}
