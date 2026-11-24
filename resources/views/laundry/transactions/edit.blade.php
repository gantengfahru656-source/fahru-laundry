<x-app-layout>@section('page-title','Edit Transaksi')
<div class="page-heading"><div><h1>Edit Transaksi</h1><p>Perbarui data atau status pengerjaan</p></div></div><section class="panel"><form method="POST" action="{{ route('transactions.update',$laundryTransaction->id) }}">@csrf @method('PUT')
<div class="form-grid">@include('laundry.transactions.form')</div><div class="form-actions"><a class="button secondary" href="{{ route('transactions.index') }}">Batal</a><button class="button primary">Simpan Perubahan</button></div></form></section></x-app-layout>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const customers = @json($customers->map(fn ($customer) => ['id' => $customer->id, 'label' => $customer->name.' - '.$customer->phone])->values());
    const customerSearch = document.querySelector('[name=customer_search]');
    const customerId = document.querySelector('[name=customer_id]');
    const service = document.querySelector('[name=laundry_service_id]');
    const weight = document.querySelector('[name=quantity]');
    const price = document.querySelector('[name=price_per_kg]');
    const total = document.querySelector('[name=total_amount]');
    const quantityLabel = document.querySelector('#quantity-label');
    const priceLabel = document.querySelector('#price-label');

    const syncCustomer = () => {
        const match = customers.find((customer) => customer.label.toLowerCase() === customerSearch.value.trim().toLowerCase());
        customerId.value = match ? match.id : '';
        customerSearch.setCustomValidity(match ? '' : 'Pilih pelanggan yang tersedia dari daftar.');
    };

    const calculate = () => {
        const option = service.options[service.selectedIndex];
        quantityLabel.textContent = option?.dataset.unit === 'pcs' ? 'Jumlah (pcs)' : 'Berat (kg)';
        priceLabel.textContent = option?.dataset.unit === 'pcs' ? 'Harga per pcs' : 'Harga per kg';
        const numericWeight = Math.max(0, Number(weight.value) || 0);
        price.value = option?.dataset.price || price.value;
        total.value = numericWeight > 0
            ? (numericWeight * Number(price.value || 0)).toLocaleString('id-ID')
            : '0';
    };

    customerSearch.addEventListener('input', syncCustomer);
    customerSearch.addEventListener('change', syncCustomer);
    service.addEventListener('change', calculate);
    weight.addEventListener('input', calculate);
    syncCustomer();
    calculate();
});
</script>
