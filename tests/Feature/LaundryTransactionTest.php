<?php

use App\Models\Customer;
use App\Models\LaundryService;
use App\Models\LaundryTransaction;
use App\Models\User;

it('creates a transaction and calculates its total automatically', function () {
    $user = User::factory()->create();
    $customer = Customer::create([
        'name' => 'Pelanggan Transaksi',
        'phone' => '081234567891',
        'address' => 'Alamat Pelanggan',
    ]);
    $service = LaundryService::create([
        'name' => 'Cuci Setrika',
        'price_per_kg' => 12000,
        'price_per_unit' => 12000,
        'unit_type' => 'kg',
        'estimated_time' => '1-2 hari',
    ]);

    $response = $this->actingAs($user)->post('/transactions', [
        'customer_id' => $customer->id,
        'laundry_service_id' => $service->id,
        'quantity' => 3.5,
        'payment_status' => 'Lunas',
        'entry_date' => '2026-11-24',
        'estimated_completion' => '2026-11-25',
        'status' => 'Diterima',
        'notes' => 'Test transaksi',
    ]);

    $response->assertRedirect(route('transactions.index'));
    $this->assertDatabaseHas('laundry_transactions', [
        'customer_id' => $customer->id,
        'laundry_service_id' => $service->id,
        'quantity' => 3.5,
        'price_per_kg' => 12000,
        'total_amount' => 42000,
        'payment_status' => 'Lunas',
        'status' => 'Diterima',
    ]);
});

it('tracks a transaction publicly by invoice code', function () {
    $customer = Customer::create([
        'name' => 'Pelanggan Tracking',
        'phone' => '081234567892',
        'address' => 'Alamat Tracking',
    ]);
    $service = LaundryService::create([
        'name' => 'Express',
        'price_per_kg' => 25000,
        'price_per_unit' => 25000,
        'unit_type' => 'kg',
        'estimated_time' => '5 jam',
    ]);
    $transaction = LaundryTransaction::create([
        'transaction_code' => 'LD-TRACK-001',
        'customer_id' => $customer->id,
        'laundry_service_id' => $service->id,
        'quantity' => 2,
        'weight' => 2,
        'price_per_kg' => 25000,
        'total_amount' => 50000,
        'entry_date' => '2026-11-24',
        'estimated_completion' => '2026-11-24',
        'status' => 'Dicuci',
        'payment_status' => 'Belum Bayar',
        'unit_type' => 'kg',
    ]);

    $response = $this->get('/tracking?code='.$transaction->transaction_code);

    $response->assertOk();
    $response->assertSee('LD-TRACK-001');
    $response->assertSee('Dicuci');
});

it('renders the edit form for an existing transaction', function () {
    $user = User::factory()->create();
    $customer = Customer::create([
        'name' => 'Pelanggan Edit',
        'phone' => '081234567893',
        'address' => 'Alamat Edit',
    ]);
    $service = LaundryService::create([
        'name' => 'Layanan Edit',
        'price_per_kg' => 10000,
        'price_per_unit' => 10000,
        'unit_type' => 'kg',
        'estimated_time' => '1 hari',
    ]);
    $transaction = LaundryTransaction::create([
        'transaction_code' => 'LD-EDIT-001',
        'customer_id' => $customer->id,
        'laundry_service_id' => $service->id,
        'quantity' => 2,
        'weight' => 2,
        'price_per_kg' => 10000,
        'total_amount' => 20000,
        'entry_date' => '2026-11-24',
        'estimated_completion' => '2026-11-25',
        'status' => 'Diterima',
        'payment_status' => 'Belum Bayar',
        'unit_type' => 'kg',
    ]);

    $response = $this->actingAs($user)->get('/transactions/'.$transaction->id.'/edit');

    $response->assertOk();
    $response->assertSee('Edit Transaksi');
    $response->assertSee('/transactions/'.$transaction->id, false);
});

it('renders the service detail and edit routes', function () {
    $user = User::factory()->create();
    $service = LaundryService::create([
        'name' => 'Layanan URL Test',
        'price_per_kg' => 9000,
        'price_per_unit' => 9000,
        'unit_type' => 'kg',
        'estimated_time' => '1 hari',
    ]);

    $detail = $this->actingAs($user)->get('/services/'.$service->id);
    $edit = $this->actingAs($user)->get('/services/'.$service->id.'/edit');

    $detail->assertOk();
    $detail->assertSee('/services/'.$service->id.'/edit', false);
    $edit->assertOk();
    $edit->assertSee('/services/'.$service->id, false);
});
