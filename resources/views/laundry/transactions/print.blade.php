<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Nota {{ $laundryTransaction->transaction_code }}</title>
    <style>
        @page { size: 80mm auto; margin: 4mm; }
        body { width:72mm;font-family:Arial,sans-serif;font-size:11px;color:#111;margin:0 auto}
        .center{text-align:center}.brand{font-size:16px;font-weight:bold}.line{border-top:1px dashed #111;margin:7px 0}.row{display:flex;justify-content:space-between;gap:8px;margin:4px 0}.small{font-size:9px}.total{font-size:14px;font-weight:bold}@media print{button{display:none}}
    </style>
</head>
<body>
    <div class="center"><div class="brand">LAUNDRY PAHRUU</div><div class="small">Struk Transaksi Laundry</div></div>
    <div class="line"></div>
    <div class="row"><span>Invoice</span><strong>{{ $laundryTransaction->transaction_code }}</strong></div>
    <div class="row"><span>Tanggal</span><span>{{ $laundryTransaction->entry_date->format('d/m/Y') }}</span></div>
    <div class="row"><span>Pelanggan</span><span>{{ $laundryTransaction->customer->name }}</span></div>
    <div class="line"></div>
    <div class="row"><span>{{ $laundryTransaction->laundryService->name }}</span><span>{{ $laundryTransaction->quantity ?? $laundryTransaction->weight }} {{ $laundryTransaction->unit_type ?? 'kg' }}</span></div>
    <div class="row"><span>Harga</span><span>Rp {{ number_format($laundryTransaction->price_per_kg,0,',','.') }}</span></div>
    <div class="line"></div>
    <div class="row total"><span>TOTAL</span><span>Rp {{ number_format($laundryTransaction->total_amount,0,',','.') }}</span></div>
    <div class="row"><span>Pembayaran</span><strong>{{ $laundryTransaction->payment_status }}</strong></div>
    <div class="row"><span>Status</span><strong>{{ $laundryTransaction->status }}</strong></div>
    <div class="line"></div>
    <div class="center small">Estimasi selesai: {{ $laundryTransaction->estimated_completion->format('d/m/Y') }}<br>Cek status: {{ url('/tracking') }}?code={{ $laundryTransaction->transaction_code }}<br>Terima kasih</div>
    <br><button onclick="window.print()">Cetak Nota</button>
</body>
</html>
