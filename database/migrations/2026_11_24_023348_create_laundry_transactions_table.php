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
        Schema::create('laundry_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('laundry_service_id')->constrained()->restrictOnDelete();
            $table->decimal('weight', 8, 2);
            $table->unsignedInteger('price_per_kg');
            $table->unsignedBigInteger('total_amount');
            $table->date('entry_date');
            $table->date('estimated_completion');
            $table->enum('status', ['Masuk', 'Proses', 'Selesai', 'Diambil'])->default('Masuk');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laundry_transactions');
    }
};
