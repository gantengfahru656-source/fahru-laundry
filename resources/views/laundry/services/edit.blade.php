<x-app-layout>
@section('page-title','Edit Layanan')
<div class="page-heading"><div><h1>Edit Layanan</h1><p>Perbarui informasi layanan</p></div></div>
<section class="panel"><form method="POST" action="{{ url('/services/'.$laundryService->id) }}">@csrf @method('PUT')
<div class="form-grid">@include('laundry.services.form')</div><div class="form-actions"><a class="button secondary" href="{{ route('services.index') }}">Batal</a><button class="button primary" type="submit">Simpan Perubahan</button></div></form></section>
</x-app-layout>
