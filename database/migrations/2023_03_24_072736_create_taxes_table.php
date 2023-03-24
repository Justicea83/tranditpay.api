<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('country_code')->nullable();
            $table->foreignId('merchant_id')->nullable()->constrained();
            $table->foreignId('payment_api_id')->nullable()->constrained();
            $table->foreignId('payment_mode_id')->nullable()->constrained();
            $table->string('name')->nullable();
            $table->enum('rate_type', ['percentage', 'fixed'])->nullable(); // Percentage or fixed
            $table->double('rate_amount')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
