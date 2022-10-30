<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseChallanItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_challan_items', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('purchase_challan_id');
            $table->unsignedBigInteger('purchase_item_id');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('purchase_challan_id')->references('id')->on('purchase_challans')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('purchase_challan_items');
        Schema::enableForeignKeyConstraints();
    }
}

