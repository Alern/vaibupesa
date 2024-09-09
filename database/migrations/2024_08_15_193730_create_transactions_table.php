<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_ref_id')->unique();
            $table->string('transaction_ref_code');
            $table->unsignedBigInteger('sender_user_id');
            $table->foreign('sender_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('receiver_user_id');
            $table->foreign('receiver_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('transaction_type_id');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('transaction_amount');
            $table->integer('transaction_cost');
            $table->string('transaction_code');
            $table->integer('sender_balance');
            $table->integer('receiver_balance');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
