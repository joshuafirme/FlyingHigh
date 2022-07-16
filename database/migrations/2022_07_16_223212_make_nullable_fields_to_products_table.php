<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('bufferStock')->nullable()->change();
            $table->decimal('conversionFactor', 8,2)->nullable()->change();
            $table->decimal('height', 8,2)->nullable()->change();
            $table->decimal('width', 8,2)->nullable()->change();
            $table->decimal('depth', 8,2)->nullable()->change();
            $table->decimal('weight', 8,2)->nullable()->change();
            $table->decimal('retailPrice', 11,2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
