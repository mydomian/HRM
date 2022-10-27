<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_items', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('receipt_id');
            $table->string('receipt_invoice_no');
            $table->unsignedBigInteger('purchase_item_id');
            $table->bigInteger('order');
            $table->bigInteger('receipt');
            $table->bigInteger('pending');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('receipt_id')->references('id')->on('receipts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('purchase_item_id')->references('id')->on('purchase_items')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipt_items');
    }
}
