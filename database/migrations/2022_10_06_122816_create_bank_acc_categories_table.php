<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_acc_categories', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->bigInteger('purchase_owner_id')->unsigned();
            $table->string('bank_acc_category');
            $table->enum('status',['active', 'deactive'])->default('active');
            $table->bigInteger('view_id')->nullable();
            $table->timestamps();
            $table->foreign('purchase_owner_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_acc_categories');
    }
}
