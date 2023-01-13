<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_title');
            $table->unsignedBigInteger('user_id');
            $table->string('status');
            $table->string('tx_ref');
            $table->string('response_code');
            $table->decimal('amount');
            $table->string('flw_ref')->nullable();
            $table->string('transaction_id');
            $table->string('currency');
            $table->dateTimeTz('payment_date');
            $table->foreign('user_id')->references('id')->on('users');  
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
        Schema::dropIfExists('payments');
    }
};
