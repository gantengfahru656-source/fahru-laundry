<x-app-layout>
    @section('page-title', 'Dashboard')
    <div class="page-heading"><div><h1>Ringkasan Usaha</h1><p>Pantau aktivitas laundry hari ini</p></div><a class="button primary" href="{{ route('transactions.create') }}">+ Transaksi Baru</a></div>
    <div class="stat-grid">
        <div class="stat-card blue"><div class="stat-icon">▣</div><div><span>Transaksi Hari Ini</span><strong>{{ number_format($stats['transactions_today']) }}</strong></div></div>
        <div class="stat-card cyan"><div class="stat-icon">◷</div><div><span>Sedang Diproses</span><strong>{{ number_format($stats['processing']) }}</strong></div></div>
        <div class="stat-card amber"><div class="stat-icon">◉</div><div><span>Siap Diambil</span><strong>{{ number_format($stats['ready']) }}</strong></div></div>
        <div class="stat-card green"><div class="stat-icon">Rp</div><div><span>Pendapatan Bulan Ini</span><strong>{{ number_format($stats['revenue'], 0, ',', '.') }}</strong></div></div>
    </div>
    <div class="dashboard-grid">
        <section class="panel"><div class="panel-heading"><div><h2>Grafik Pendapatan</h2><p>Pendapatan 12 periode terakhir</p></div><strong class="revenue">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</strong></div>
            <div class="chart">
                @php $max = max(1, $revenue->max('total')); @endphp
                @forelse($revenue as $item)
                    <div class="chart-column"><div class="chart-bar" style="height: {{ max(8, ($item->total / $max) * 180) }}px"></div><span>{{ $item->month }}</span></div>
                @empty
                    <div class="empty-chart">Belum ada pendapatan tercatat</div>
                @endforelse
            </div>
        </section>
        <section class="panel status-panel"><h2>Status Pengerjaan</h2><div class="status-list">
            @foreach(['diterima' => 'Diterima', 'dicuci' => 'Dicuci', 'disetrika' => 'Disetrika', 'siap_diambil' => 'Siap Diambil', 'selesai' => 'Selesai'] as $key => $label)
                @php $count = $statusCounts->{$key} ?? 0; @endphp
                <div><span class="status-dot {{ str_replace('_', '-', $key) }}"></span><span>{{ $label }}</span><strong>{{ $count }}</strong></div>
            @endforeach
        </div></section>
    </div>
    <section class="panel table-panel"><div class="panel-heading"><div><h2>Transaksi Terbaru</h2><p>Daftar cucian yang baru dicatat</p></div><a href="{{ route('transactions.index') }}">Lihat semua</a></div>
        <div class="table-wrap"><table><thead><tr><th>Kode</th><th>Pelanggan</th><th>Layanan</th><th>Total</th><th>Status</th></tr></thead><tbody>
        @forelse($recentTransactions as $transaction)
            <tr><td><strong>{{ $transaction->transaction_code }}</strong></td><td>{{ $transaction->customer->name }}</td><td>{{ $transaction->laundryService->name }}</td><td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td><td><span class="badge status-{{ strtolower($transaction->status) }}">{{ $transaction->status }}</span></td></tr>
        @empty
            <tr><td colspan="5" class="empty-row">Belum ada transaksi</td></tr>
        @endforelse
        </tbody></table></div>
    </section>
</x-app-layout>
