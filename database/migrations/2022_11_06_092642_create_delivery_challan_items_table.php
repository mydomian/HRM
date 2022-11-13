<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryChallanItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_challan_items', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->string('sale_invoice_no');
            $table->string('delivery_invoice_no');
            $table->unsignedBigInteger('delivery_challan_id');
            $table->string('delivery_challan_invoice_no');
            $table->unsignedBigInteger('delivery_item_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('avg_qty');
            $table->string('bag');
            $table->bigInteger('qty');
            $table->bigInteger('rate');
            $table->bigInteger('amount');
            $table->bigInteger('order');
            $table->bigInteger('receipt');
            $table->bigInteger('pending');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('delivery_challan_id')->references('id')->on('delivery_challans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('delivery_item_id')->references('id')->on('delivery_items')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('delivery_challan_items');
        Schema::enableForeignKeyConstraints();
    }
}
