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
        Schema::create('settlement_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained();
            $table->foreignId('settlement_mode_id')->constrained();
            $table->string('bank_name');
            $table->string('account_name')->nullable();
            $table->string('account_number'); // Phone number for mobile money
            $table->json('extra_data')->nullable();
            $table->unique(['settlement_mode_id', 'merchant_id'], 'uniq_c');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlement_banks');
    }
};
