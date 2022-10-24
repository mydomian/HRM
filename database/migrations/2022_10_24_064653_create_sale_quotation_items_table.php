<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleQuotationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_quotation_items', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->unsignedBigInteger('sale_quotation_id');
            $table->unsignedBigInteger('unit_id');
            $table->string('quotation_invoice_no');
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('avg_qty');
            $table->string('bag');
            $table->bigInteger('qty');
            $table->bigInteger('rate');
            $table->bigInteger('amount');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sale_quotation_id')->references('id')->on('sale_quotations')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('sale_quotation_items');
        Schema::enableForeignKeyConstraints();
    }
}
