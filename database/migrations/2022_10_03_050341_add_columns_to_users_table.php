<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->after('id');
            $table->string('company_name')->after('package_id');
            $table->string('payment_type')->after('company_name');
            $table->text('account_no')->after('payment_type');
            $table->text('transaction_id')->after('account_no');
            $table->bigInteger('duration')->after('transaction_id');
            $table->date('start_date')->after('duration')->nullable();
            $table->date('end_date')->after('start_date')->nullable();
            $table->text('database_name')->after('end_date')->nullable();
            $table->enum('status',['active', 'deactive'])->after('database_name')->default('deactive');
            $table->date('date')->after('status');
            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
