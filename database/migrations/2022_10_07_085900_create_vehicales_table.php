<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicales', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('package_buy_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('thana_id');
            $table->unsignedBigInteger('union_id');
            $table->string('vehicle_name');
            $table->string('vehicle_type');
            $table->string('vehicle_no')->unique();
            $table->string('vehicle_reg_no')->unique();
            $table->string('owner_name');
            $table->string('father_name');
            $table->bigInteger('owner_phone')->unique();
            $table->string('owner_post_office');
            $table->string('owner_village');
            $table->enum('status',['active', 'deactive'])->default('active');
            $table->timestamps();

            $table->foreign('package_buy_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('thana_id')->references('id')->on('thanas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('union_id')->references('id')->on('unions')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('vehicales');
        Schema::enableForeignKeyConstraints();
    }
}
