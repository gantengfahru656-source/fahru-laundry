# Laundry Pahruu

Sistem Informasi Manajemen Laundry berbasis **Laravel 13**, MySQL, dan Tailwind CSS.

## Fitur

- Autentikasi dengan role Admin dan Kasir
- Dashboard transaksi, proses cucian, pendapatan, dan grafik
- CRUD layanan laundry dengan satuan kg atau pcs
- CRUD pelanggan
- Transaksi laundry dengan perhitungan otomatis
- Status cucian dan status pembayaran
- Pelacakan invoice publik
- Cetak nota thermal
- Laporan keuangan dan export CSV
- Manajemen pengguna

## Instalasi

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Atur koneksi database MySQL pada file `.env`, lalu jalankan aplikasi di `http://127.0.0.1:8000`.

## Akun Demo

| Role | Email | Password |
|---|---|---|
| Admin | admin@laundry.com | password |
| Kasir | kasir@laundry.com | password |

## Pelacakan Publik

Buka `/tracking` dan masukkan kode invoice transaksi.

## Pengujian

```bash
php artisan test
```
