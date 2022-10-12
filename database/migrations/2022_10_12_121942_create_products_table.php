<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->bigInteger('package_buy_id')->unsigned();
            $table->bigInteger('acc_cus_sup_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned();
            $table->bigInteger('categroy_id')->unsigned();
            $table->bigInteger('unit_id')->unsigned();
            $table->bigInteger('lot_gallary_id')->unsigned();
            $table->string('product_name');
            $table->string('product_model');
            $table->string('product_image');
            $table->string('batch_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->bigInteger('khat_acc_id')->nullable();
            $table->bigInteger('supplier_price');
            $table->bigInteger('our_price');
            $table->enum('status',['active', 'deactive'])->default('active');
            $table->bigInteger('view_id')->nullable();
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('acc_cus_sup_id')->references('id')->on('acc_customer_suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('categroy_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lot_gallary_id')->references('id')->on('lot_gallaries')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
