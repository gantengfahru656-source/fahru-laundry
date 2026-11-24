<x-app-layout>
@section('page-title','Tambah Layanan')
<div class="page-heading"><div><h1>Tambah Layanan</h1><p>Masukkan informasi layanan baru</p></div></div>
<section class="panel"><form method="POST" action="{{ route('services.store') }}">@csrf
<div class="form-grid">@include('laundry.services.form')</div><div class="form-actions"><a class="button secondary" href="{{ route('services.index') }}">Batal</a><button class="button primary" type="submit">Simpan Layanan</button></div></form></section>
</x-app-layout>
