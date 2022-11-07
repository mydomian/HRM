<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptChallanItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_challan_items', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->string('receipt_invoice_no');
            $table->unsignedBigInteger('receipt_challan_id');
            $table->unsignedBigInteger('receipt_item_id');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('receipt_challan_id')->references('id')->on('receipt_challans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('receipt_item_id')->references('id')->on('receipt_items')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('receipt_challan_items');
        Schema::enableForeignKeyConstraints();
    }
}
