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
        Schema::create('agentmerchants', function (Blueprint $table) {
            $table->id();
            $table->string('entitytype');
            $table->string('identcode');
            $table->string('orgname');
            $table->string('orglocation');
            $table->string('orgdescription');
            $table->integer('orgbalance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agentmerchants');
    }
};
