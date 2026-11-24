<x-app-layout>@section('page-title','Tambah Pengguna')
<div class="page-heading"><div><h1>Tambah Pengguna</h1><p>Tambahkan akun untuk admin atau kasir</p></div></div><section class="panel"><form method="POST" action="{{ route('users.store') }}">@csrf
<div class="form-grid">@include('laundry.users.form')</div><div class="form-actions"><a class="button secondary" href="{{ route('users.index') }}">Batal</a><button class="button primary">Simpan Pengguna</button></div></form></section></x-app-layout>
