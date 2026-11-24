<div class="form-group"><label>Nama</label><input name="name" value="{{ old('name',$user->name ?? '') }}" required></div>
<div class="form-group"><label>Email</label><input type="email" name="email" value="{{ old('email',$user->email ?? '') }}" required></div>
<div class="form-group"><label>Password{{ isset($user)?' (kosongkan jika tidak diubah)':'' }}</label><input type="password" name="password" {{ isset($user)?'':'required' }}></div>
<div class="form-group"><label>Role</label><select name="role" required><option value="kasir" {{ old('role',$user->role ?? 'kasir')==='kasir'?'selected':'' }}>Kasir</option><option value="admin" {{ old('role',$user->role ?? '')==='admin'?'selected':'' }}>Admin</option></select></div>
