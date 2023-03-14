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
        Schema::create('payment_api_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_api_id')->constrained();
            $table->foreignId('payment_mode_id')->constrained();
            $table->boolean('fixed')->default(false);
            $table->decimal('charge')->default(0.00); // If it's not fixed then its a percentage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_api_charges');
    }
};
