<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_items', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('delivery_id');
            $table->string('delivery_invoice_no');
            $table->unsignedBigInteger('sale_item_id');
            $table->bigInteger('order');
            $table->bigInteger('receipt');
            $table->bigInteger('pending');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sale_item_id')->references('id')->on('sale_items')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('delivery_items');
        Schema::enableForeignKeyConstraints();
    }
}
