<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->unsignedBigInteger('sale_id');
            $table->string('delivery_invoice_no');
            $table->unsignedBigInteger('ware_house_id');
            $table->unsignedBigInteger('vehicale_id');
            $table->longText('delivery_details');
            $table->bigInteger('total_qty');
            $table->bigInteger('total_receipt');
            $table->bigInteger('total_pending');
            $table->date('delivery_date');
            $table->enum('status',['pending', 'accept','delivered'])->default('pending');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ware_house_id')->references('id')->on('ware_houses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('vehicale_id')->references('id')->on('vehicales')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('deliveries');
        Schema::enableForeignKeyConstraints();
    }
}
