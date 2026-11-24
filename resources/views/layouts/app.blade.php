<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
            <div class="laundry-shell" x-data="{ sidebarOpen: false }">
                <aside class="sidebar" :class="{ 'sidebar-open': sidebarOpen }">
                    <div class="brand">
                        <div class="brand-mark">LP</div>
                        <div><strong>Laundry Pahruu</strong><span>Management</span></div>
                    </div>
                    <nav class="sidebar-nav">
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span>⌂</span> Dashboard</a>
                        <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}"><span>◫</span> Data Layanan</a>
                        <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}"><span>♙</span> Data Pelanggan</a>
                        <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}"><span>▣</span> Transaksi Laundry</a>
                        <a href="{{ route('transactions.recap') }}" class="{{ request()->is('transactions-recap') ? 'active' : '' }}"><span>▤</span> Rekap Transaksi</a>
                        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}"><span>▥</span> Laporan Keuangan</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}"><span>♟</span> Manajemen Pengguna</a>
                        @endif
                    </nav>
                    <form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
                        @csrf
                        <button type="submit">↪ Logout</button>
                    </form>
                </aside>
                <button class="sidebar-backdrop" @click="sidebarOpen = false" :class="{ 'show': sidebarOpen }"></button>
                <div class="main-area">
                    <header class="topbar">
                        <button class="menu-button" @click="sidebarOpen = !sidebarOpen">☰</button>
                        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                        <div class="user-pill"><div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><div><strong>{{ auth()->user()->name }}</strong><span>{{ ucfirst(auth()->user()->role) }}</span></div></div>
                    </header>
                    <main class="content">
                        @hasSection('page-title')
                            <div class="motivation-banner">
                                <div class="motivation-bubble bubble-one"></div>
                                <div class="motivation-bubble bubble-two"></div>
                                <div class="motivation-bubble bubble-three"></div>
                                <div class="motivation-copy">
                                    <span>DAILY MOTIVATION</span>
                                    <strong>Fresh service, fresh spirit.</strong>
                                    <p>Rapikan cucian. Layani pelanggan. Kembangkan usaha setiap hari.</p>
                                </div>
                                <div class="motivation-mark">LP</div>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="alert success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert error">{{ session('error') }}</div>
                        @endif
                        {{ $slot }}
                    </main>
                </div>
            </div>
    </body>
</html>
