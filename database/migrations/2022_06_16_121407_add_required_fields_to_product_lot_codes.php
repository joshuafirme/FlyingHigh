<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequiredFieldsToProductLotCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_lot_codes', function (Blueprint $table) {
            $table->string('uom')->after('expiration');
            $table->string('location')->after('expiration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_lot_codes', function (Blueprint $table) {
            //
        });
    }
}
