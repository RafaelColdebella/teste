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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignId('specie_id')->constrained('species')->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('value', 15, 2)->default(0);
            $table->decimal('transaction_tax', 15, 2)->default(0);
            $table->string('status', 20)->default('pending');
            $table->foreignId('paid_by')->constrained('participants')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('received_by')->constrained('participants')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
