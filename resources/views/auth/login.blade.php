<x-guest-layout>
    <div class="login-page">
        <div class="login-card">
            <div class="login-brand"><div class="brand-mark">LP</div><div><strong>Laundry Pahruu</strong><span>Sistem Manajemen Laundry</span></div></div>
            <h1>Selamat Datang</h1>
            <p>Masuk untuk mengelola operasional laundry.</p>
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <label class="remember"><input type="checkbox" name="remember"><span>Ingat saya</span></label>
                <button type="submit" class="login-button">Masuk</button>
            </form>
        </div>
    </div>
</x-guest-layout>
