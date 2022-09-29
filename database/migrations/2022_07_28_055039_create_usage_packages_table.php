<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsagePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usage_packages', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->bigInteger('user_id');
            $table->bigInteger('package_id');
            $table->string('company_name');
            $table->string('payment_type');
            $table->text('account_no');
            $table->text('transaction_id');
            $table->float('amount');
            $table->bigInteger('duration');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('database_name')->nullable();
            $table->enum('status',['active', 'deactive'])->default('deactive');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usage_packages');
    }
}
