<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('stock_now')->nullable();
            $table->bigInteger('stock_old')->nullable();
            $table->bigInteger('sale_price')->nullable();
            $table->bigInteger('purchase_price')->nullable();
            $table->bigInteger('production_price')->nullable();
            $table->bigInteger('production_qty')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('stocks');
        Schema::enableForeignKeyConstraints();
    }
}
