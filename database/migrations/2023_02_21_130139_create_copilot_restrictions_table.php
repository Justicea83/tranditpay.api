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
        Schema::create('copilot_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('copilot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('restriction_id')->constrained();
            $table->unique(['copilot_id', 'restriction_id']);
            $table->json('extra_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copilot_restrictions');
    }
};
