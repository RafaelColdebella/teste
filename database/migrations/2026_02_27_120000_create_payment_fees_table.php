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
        Schema::create('payment_fees', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->unsignedBigInteger('payer_id');
            $table->unsignedBigInteger('receiver_id');
            $table->decimal('value', 12, 2);
            $table->timestamps();

            $table->foreign('payer_id')->references('id')->on('participants');
            $table->foreign('receiver_id')->references('id')->on('participants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_fees');
    }
};
