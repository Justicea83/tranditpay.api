<?php

use App\Utils\Payments\Enums\FundsLocation;
use App\Utils\Payments\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->double('amount');
            $table->double('tax_amount');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('merchant_id')->constrained();
            $table->string('status', 30)->default(TransactionStatus::Pending->value);
            $table->string('funds_location', 30)->nullable()->default(FundsLocation::Application->value);
            $table->string('payment_method')->nullable();
            $table->string('currency');
            $table->string('reference');
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
