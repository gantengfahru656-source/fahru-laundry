<x-app-layout>@section('page-title','Tambah Pelanggan')
<div class="page-heading"><div><h1>Tambah Pelanggan</h1><p>Catat data pelanggan baru</p></div></div><section class="panel"><form method="POST" action="{{ route('customers.store') }}">@csrf
<div class="form-grid">@include('laundry.customers.form')</div><div class="form-actions"><a class="button secondary" href="{{ route('customers.index') }}">Batal</a><button class="button primary">Simpan Pelanggan</button></div></form></section></x-app-layout>
