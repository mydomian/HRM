<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->unsignedBigInteger('acc_cus_sup_id');
            $table->string('purchase_invoice_no');
            $table->bigInteger('product_order_by_id');
            $table->string('receipt_invoice_no')->nullable();
            $table->string('challan_invoice_no')->nullable();
            $table->date('purchase_date');
            $table->longText('purchase_details');
            $table->bigInteger('total_qty');
            $table->bigInteger('total_purchase_amount');
            $table->bigInteger('tax_amount');
            $table->bigInteger('total_tax_amount');
            $table->bigInteger('service_charge');
            $table->bigInteger('shipping_cost');
            $table->bigInteger('grand_total');
            $table->bigInteger('paid_amount');
            $table->bigInteger('due_amount');
            $table->unsignedBigInteger('payment_method_id');
            $table->longText('document');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('acc_cus_sup_id')->references('id')->on('acc_customer_suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('purchases');
        Schema::enableForeignKeyConstraints();
    }
}
