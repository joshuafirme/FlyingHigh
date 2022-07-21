<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToHubTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hub_transfer', function (Blueprint $table) {
         
            $table->string('lot_code')->nullable()->after('sku');
            $table->string('warehouse_id')->after('hub_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hub_transfer', function (Blueprint $table) {
            //
        });
    }
}
