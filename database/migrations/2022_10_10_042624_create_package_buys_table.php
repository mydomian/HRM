<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_buys', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('company_name')->unique();
            $table->string('payment_type');
            $table->text('account_no');
            $table->text('transaction_id');
            $table->bigInteger('duration');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('database_name')->nullable();
            $table->string('password')->nullable();
            $table->enum('status',['pending','active', 'deactive'])->default('pending');
            $table->date('date');
            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('package_buys');
        Schema::enableForeignKeyConstraints();
    }
}
