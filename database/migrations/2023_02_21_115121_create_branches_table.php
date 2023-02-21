<?php

use App\Utils\MerchantUtils;
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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();
            $table->longText('location')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('merchant_id')->constrained();
            $table->float('lat')->nullable();
            $table->float('lng')->nullable();
            $table->string('status')->nullable()
                ->default(MerchantUtils::MERCHANT_STATUS_ACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
