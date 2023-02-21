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
        Schema::create('copilots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained();
            $table->boolean('blocked')->default(false);
            $table->timestamp('suspended_until')->nullable();
            $table->foreignId('pilot_id')->constrained('users');
            $table->unique(['merchant_id', 'pilot_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('copilots');
    }
};
