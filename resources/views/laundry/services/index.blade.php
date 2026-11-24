<x-app-layout>
@section('page-title','Data Layanan')
<div class="page-heading"><div><h1>Data Layanan</h1><p>Kelola jenis dan harga layanan laundry</p></div><a class="button primary" href="{{ route('services.create') }}">+ Tambah Layanan</a></div>
<section class="panel"><div class="table-wrap"><table><thead><tr><th>ID</th><th>Nama Layanan</th><th>Harga/kg</th><th>Estimasi</th><th>Aksi</th></tr></thead><tbody>
@forelse($services as $service)<tr><td>#{{ $service->id }}</td><td><strong>{{ $service->name }}</strong></td><td>Rp {{ number_format($service->price_per_unit ?? $service->price_per_kg,0,',','.') }} / {{ $service->unit_type ?? 'kg' }}</td><td>{{ $service->estimated_time }}</td><td><a class="button secondary" href="{{ route('services.show',$service->id) }}">Detail</a> <a class="button secondary" href="{{ route('services.edit',$service->id) }}">Edit</a></td></tr>
@empty<tr><td colspan="5" class="empty-row">Belum ada layanan</td></tr>@endforelse</tbody></table></div><div class="pagination">{{ $services->links() }}</div></section>
</x-app-layout>
