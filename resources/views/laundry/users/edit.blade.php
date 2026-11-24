<x-app-layout>@section('page-title','Edit Pengguna')
<div class="page-heading"><div><h1>Edit Pengguna</h1><p>Perbarui akun pengguna</p></div></div><section class="panel"><form method="POST" action="{{ route('users.update',$user->id) }}">@csrf @method('PUT')
<div class="form-grid">@include('laundry.users.form')</div><div class="form-actions"><a class="button secondary" href="{{ route('users.index') }}">Batal</a><button class="button primary">Simpan Perubahan</button></div></form></section></x-app-layout>
