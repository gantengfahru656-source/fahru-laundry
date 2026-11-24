<x-app-layout>@section('page-title','Edit Pelanggan')
<div class="page-heading"><div><h1>Edit Pelanggan</h1><p>Perbarui data pelanggan</p></div></div><section class="panel"><form method="POST" action="{{ route('customers.update',$customer->id) }}">@csrf @method('PUT')
<div class="form-grid">@include('laundry.customers.form')</div><div class="form-actions"><a class="button secondary" href="{{ route('customers.index') }}">Batal</a><button class="button primary">Simpan Perubahan</button></div></form></section></x-app-layout>
