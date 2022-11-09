<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_challans', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->string('purchase_invoice_no');
            $table->string('receipt_invoice_no');
            $table->string('receipt_challan_invoice_no');
            $table->unsignedBigInteger('vehicale_id');
            $table->longText('challan_details');
            $table->date('challan_date');
            $table->string('document');
            $table->enum('status',['pending', 'accept'])->default('pending');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('receipt_challans');
        Schema::enableForeignKeyConstraints();
    }
}
