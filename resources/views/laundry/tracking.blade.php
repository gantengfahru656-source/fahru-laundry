<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Status Laundry</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
<main class="login-page">
    <section class="login-card" style="width:min(620px,100%)">
        <div class="login-brand"><div class="brand-mark">LP</div><div><strong>Laundry Pahruu</strong><span>Lacak Status Cucian</span></div></div>
        <h1>Cek Status Cucian</h1>
        <p>Masukkan kode invoice pada nota laundry Anda.</p>
        <form method="GET" action="{{ route('tracking') }}" class="search" style="width:100%">
            <input name="code" value="{{ $code }}" placeholder="Contoh: LD-20261124-ABC1" required style="width:100%;border-right:1px solid #94a3b8;border-radius:8px 0 0 8px">
            <button type="submit">Cari</button>
        </form>
        @if($code !== '' && !$transaction)
            <div class="alert error" style="margin-top:16px">Kode invoice tidak ditemukan.</div>
        @elseif($transaction)
            <div class="detail-grid" style="margin-top:18px">
                <div class="detail-item"><span>Kode Invoice</span><strong>{{ $transaction->transaction_code }}</strong></div>
                <div class="detail-item"><span>Status</span><strong>{{ $transaction->status }}</strong></div>
                <div class="detail-item"><span>Pelanggan</span><strong>{{ $transaction->customer->name }}</strong></div>
                <div class="detail-item"><span>Layanan</span><strong>{{ $transaction->laundryService->name }}</strong></div>
                <div class="detail-item"><span>Jumlah</span><strong>{{ $transaction->quantity ?? $transaction->weight }} {{ $transaction->unit_type ?? 'kg' }}</strong></div>
                <div class="detail-item"><span>Pembayaran</span><strong>{{ $transaction->payment_status }}</strong></div>
                <div class="detail-item full"><span>Estimasi Selesai</span><strong>{{ $transaction->estimated_completion->format('d/m/Y') }}</strong></div>
            </div>
        @endif
    </section>
</main>
</body>
</html>
