![Iuranku](public/logo_binus_resize.png "Iuranku")

Aplikasi Manajemen Iuran Berbasis Web untuk Pengelolaan Keuangan Warga - Iuranku

<hr>

## Daftar Isi
1. [Instalasi](#instalasi)
    - [Spesifikasi yang Dibutuhkan](#spesifikasi)
    - [Cara Install](#cara-install)
2. [Screenshots](#screenshots)

<hr>

## Instalasi

### Spesifikasi
- PHP ^8
- Laravel 10
- Database MySQL atau MariaDB
- SQlite (untuk `automated testing`)

### Cara Install

1. Clone atau download source code
    - Para terminal, clone repo `git clone https://github.com/nipengg/iuranku-api.git`
    - Jika tidak menggunakan Git, silakan **Download Zip** dan *extract* pada direktori web server (misal: xampp/htdocs)
    - Jika menggunakan laragon silakan extract pada direktori laragon/www
2. `cd iuranku`
3. `composer install`
4. `cp .env.example .env`
    - Jika tidak menggunakan Git, bisa rename file `.env.example` menjadi `.env`
5. Pada terminal `php artisan key:generate`
6. Buat **database pada mysql** untuk aplikasi ini
7. **Setting database** pada file `.env`
8. `php artisan serve`
9. Selesai

## Screenshots