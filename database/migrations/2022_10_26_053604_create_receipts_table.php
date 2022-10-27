<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->unsignedBigInteger('purchase_id');
            $table->string('receipt_invoice_no');
            $table->unsignedBigInteger('ware_house_id');
            $table->unsignedBigInteger('vehicale_id');
            $table->longText('receipt_details');
            $table->bigInteger('total_qty');
            $table->bigInteger('total_receipt');
            $table->bigInteger('total_pending');
            $table->date('receipt_date');
            $table->enum('status',['pending', 'accept'])->default('pending');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('receipts');
        Schema::enableForeignKeyConstraints();
    }
}
