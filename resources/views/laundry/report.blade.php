<x-app-layout>
@section('page-title','Laporan Keuangan')
<div class="page-heading">
    <div><h1>Laporan Keuangan</h1><p>Filter dan ekspor transaksi laundry</p></div>
    <a class="button primary" href="{{ route('reports.export', request()->only(['start_date','end_date'])) }}">Export CSV</a>
</div>
<section class="panel">
    <form method="GET" action="{{ route('reports.index') }}" class="toolbar">
        <div style="display:flex;gap:10px">
            <input class="form-control" type="date" name="start_date" value="{{ $validated['start_date'] ?? '' }}" style="height:42px;border:2px solid #94a3b8;border-radius:8px;padding:0 10px">
            <input class="form-control" type="date" name="end_date" value="{{ $validated['end_date'] ?? '' }}" style="height:42px;border:2px solid #94a3b8;border-radius:8px;padding:0 10px">
            <button class="button primary" type="submit">Tampilkan</button>
        </div>
    </form>
    <div class="stat-grid" style="grid-template-columns:repeat(2,1fr);margin-bottom:16px">
        <div class="stat-card green"><div class="stat-icon">Rp</div><div><span>Total Pendapatan Lunas</span><strong>{{ number_format($revenue,0,',','.') }}</strong></div></div>
        <div class="stat-card amber"><div class="stat-icon">Rp</div><div><span>Belum Dibayar</span><strong>{{ number_format($outstanding,0,',','.') }}</strong></div></div>
    </div>
    <div class="table-wrap">
        <table><thead><tr><th>Invoice</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th><th>Pembayaran</th><th>Status</th></tr></thead><tbody>
        @forelse($transactions as $transaction)
            <tr><td>{{ $transaction->transaction_code }}</td><td>{{ $transaction->entry_date->format('d/m/Y') }}</td><td>{{ $transaction->customer->name }}</td><td>Rp {{ number_format($transaction->total_amount,0,',','.') }}</td><td>{{ $transaction->payment_status }}</td><td>{{ $transaction->status }}</td></tr>
        @empty
            <tr><td colspan="6" class="empty-row">Tidak ada transaksi pada periode ini</td></tr>
        @endforelse
        </tbody></table>
    </div>
    <div class="pagination">{{ $transactions->links() }}</div>
</section>
</x-app-layout>
