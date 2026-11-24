<div class="form-group"><label>Nama Pelanggan</label><input name="name" value="{{ old('name',$customer->name ?? '') }}" required>@error('name')<div class="alert error">{{ $message }}</div>@enderror</div>
<div class="form-group"><label>Nomor WhatsApp</label><input name="phone" value="{{ old('phone',$customer->phone ?? '') }}" required>@error('phone')<div class="alert error">{{ $message }}</div>@enderror</div>
<div class="form-group full"><label>Alamat</label><textarea name="address">{{ old('address',$customer->address ?? '') }}</textarea></div>
