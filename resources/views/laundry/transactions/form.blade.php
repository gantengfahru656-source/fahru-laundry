<div class="form-group">
    <label>Pelanggan</label>
    @php
        $selectedCustomer = isset($laundryTransaction)
            ? $customers->firstWhere('id', $laundryTransaction->customer_id)
            : $customers->firstWhere('id', old('customer_id'));
    @endphp
    <input
        type="text"
        name="customer_search"
        list="customer-options"
        value="{{ old('customer_search', $selectedCustomer ? $selectedCustomer->name.' - '.$selectedCustomer->phone : '') }}"
        placeholder="Ketik nama atau nomor HP"
        autocomplete="off"
        required
    >
    <datalist id="customer-options">
        @foreach($customers as $customer)
            <option value="{{ $customer->name }} - {{ $customer->phone }}"></option>
        @endforeach
    </datalist>
    <input type="hidden" name="customer_id" value="{{ old('customer_id', $laundryTransaction->customer_id ?? '') }}">
    <div style="margin-top:7px;font-size:12px;">
        Belum terdaftar? <a href="{{ route('customers.create') }}" target="_blank" style="color:#2563eb;font-weight:700;">Tambah pelanggan baru</a>
    </div>
</div>
<div class="form-group"><label>Jenis Layanan</label><select name="laundry_service_id" required><option value="">Pilih layanan</option>@foreach($services as $service)<option value="{{ $service->id }}" data-price="{{ $service->price_per_unit ?? $service->price_per_kg }}" data-unit="{{ $service->unit_type ?? 'kg' }}" {{ old('laundry_service_id',$laundryTransaction->laundry_service_id ?? '')==$service->id?'selected':'' }}>{{ $service->name }} - Rp {{ number_format($service->price_per_unit ?? $service->price_per_kg,0,',','.') }}/{{ $service->unit_type ?? 'kg' }}</option>@endforeach</select></div>
<div class="form-group"><label id="quantity-label">Berat / Jumlah</label><input type="number" step="0.01" min="0.1" name="quantity" value="{{ old('quantity',$laundryTransaction->quantity ?? $laundryTransaction->weight ?? '') }}" required></div>
<div class="form-group"><label id="price-label">Harga per Satuan</label><input name="price_per_kg" value="{{ old('price_per_kg',$laundryTransaction->price_per_kg ?? '') }}" readonly></div>
<div class="form-group"><label>Total Pembayaran</label><input name="total_amount" value="{{ old('total_amount',isset($laundryTransaction)?number_format($laundryTransaction->total_amount,0,',','.'): '') }}" readonly></div>
<div class="form-group"><label>Status Pengerjaan</label><select name="status" required>@foreach(['Diterima','Dicuci','Disetrika','Siap Diambil','Selesai'] as $status)<option {{ old('status',$laundryTransaction->status ?? 'Diterima')==$status?'selected':'' }}>{{ $status }}</option>@endforeach</select></div>
<div class="form-group"><label>Status Pembayaran</label><select name="payment_status" required>@foreach(['Lunas','Belum Bayar'] as $paymentStatus)<option {{ old('payment_status',$laundryTransaction->payment_status ?? 'Belum Bayar')==$paymentStatus?'selected':'' }}>{{ $paymentStatus }}</option>@endforeach</select></div>
<div class="form-group"><label>Tanggal Masuk</label><input type="date" name="entry_date" value="{{ old('entry_date',isset($laundryTransaction)?$laundryTransaction->entry_date->format('Y-m-d'):now()->format('Y-m-d')) }}" required></div>
<div class="form-group"><label>Estimasi Selesai</label><input type="date" name="estimated_completion" value="{{ old('estimated_completion',isset($laundryTransaction)?$laundryTransaction->estimated_completion->format('Y-m-d'):'') }}" required></div>
<div class="form-group full"><label>Catatan</label><textarea name="notes">{{ old('notes',$laundryTransaction->notes ?? '') }}</textarea></div>
