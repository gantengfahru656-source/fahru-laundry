<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laundry_services', function (Blueprint $table) {
            $table->enum('unit_type', ['kg', 'pcs'])->default('kg')->after('price_per_kg');
            $table->unsignedInteger('price_per_unit')->nullable()->after('unit_type');
        });

        Schema::table('laundry_transactions', function (Blueprint $table) {
            $table->decimal('quantity', 8, 2)->default(1)->after('laundry_service_id');
            $table->enum('payment_status', ['Lunas', 'Belum Bayar'])->default('Belum Bayar')->after('status');
            $table->string('unit_type', 10)->default('kg')->after('payment_status');
            $table->string('status', 30)->default('Diterima')->change();
        });

        DB::table('laundry_services')->whereNull('price_per_unit')->update([
            'price_per_unit' => DB::raw('price_per_kg'),
        ]);
        DB::table('laundry_transactions')->where('status', 'Masuk')->update(['status' => 'Diterima']);
        DB::table('laundry_transactions')->where('status', 'Proses')->update(['status' => 'Dicuci']);
        DB::table('laundry_transactions')->where('status', 'Diambil')->update(['status' => 'Selesai']);
        DB::table('laundry_transactions')->update([
            'quantity' => DB::raw('weight'),
            'unit_type' => 'kg',
        ]);
    }

    public function down(): void
    {
        Schema::table('laundry_transactions', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'payment_status', 'unit_type']);
        });

        Schema::table('laundry_services', function (Blueprint $table) {
            $table->dropColumn(['unit_type', 'price_per_unit']);
        });
    }
};
