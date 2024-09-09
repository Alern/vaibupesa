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
        Schema::create('charge_bands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_type_id');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->onUpdate('cascade')->onDelete('cascade');
            $table->string('chargebandname');
            $table->string('loweramount');
            $table->string('upperamount');
            $table->string('tcost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_bands');
    }
};
