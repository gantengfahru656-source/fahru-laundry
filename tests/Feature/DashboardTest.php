<?php

use App\Models\Customer;
use App\Models\LaundryService;
use App\Models\LaundryTransaction;
use App\Models\User;

it('renders the dashboard for an authenticated user', function () {
    $user = User::factory()->create();
    $customer = Customer::create([
        'name' => 'Pelanggan Test',
        'phone' => '081234567890',
        'address' => 'Alamat Test',
    ]);
    $service = LaundryService::create([
        'name' => 'Layanan Test',
        'price_per_kg' => 7000,
        'price_per_unit' => 7000,
        'unit_type' => 'kg',
        'estimated_time' => '1 hari',
        'description' => 'Deskripsi Test',
    ]);
    LaundryTransaction::create([
        'transaction_code' => 'LD-TEST-001',
        'customer_id' => $customer->id,
        'laundry_service_id' => $service->id,
        'weight' => 2,
        'quantity' => 2,
        'unit_type' => 'kg',
        'price_per_kg' => 7000,
        'total_amount' => 14000,
        'entry_date' => '2026-11-24',
        'estimated_completion' => '2026-11-25',
        'status' => 'Selesai',
        'payment_status' => 'Lunas',
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertSee('Ringkasan Usaha');
    $response->assertSee('Rp 14.000');
});
