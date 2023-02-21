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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('primary_email')->unique()->nullable();
            $table->string('primary_phone')->unique()->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->boolean('blocked')->default(false);
            $table->dateTime('suspended_until')->nullable();
            $table->foreignId('owner_id')->constrained('users');
            $table->string('domain')->nullable();
            $table->foreignId('state_id')->nullable()->constrained();
            $table->timestamp('primary_email_verified_at')->nullable();
            $table->timestamp('primary_phone_verified_at')->nullable();
            $table->longText('location')->nullable();
            $table->longText('about')->nullable();
            $table->string('website')->nullable();
            $table->string('status')->nullable()
                ->default(MerchantUtils::MERCHANT_STATUS_PENDING);
            $table->string('avatar')->nullable();
            $table->longText("address")->nullable();
            $table->json("extra_data")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
