<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccCustomerSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_customer_suppliers', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->string('acc_name');
            $table->bigInteger('acc_no')->unique();
            $table->string('email');
            $table->bigInteger('phone');
            $table->text('address');
            $table->string('word');
            $table->string('acc_area');
            $table->bigInteger('acc_opening_balance');
            $table->bigInteger('acc_hold_balance');
            $table->string('profile_image');
            $table->string('nid_image');
            $table->date('date');
            $table->enum('status',['active', 'deactive'])->default('active');
            $table->timestamps();
            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('acc_customer_suppliers');
        Schema::enableForeignKeyConstraints();
    }
}
