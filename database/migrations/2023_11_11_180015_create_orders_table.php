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
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('shipping_address');
            $table->string('total_amount');
            $table->enum('status', ['accepted', 'pending', 'rejected'])->default('pending');
            $table->enum('location', ['Arrived', 'Shipped', 'In Stock'])->default('In Stock');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('In Stock');
            $table->json('products');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
