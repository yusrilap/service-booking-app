# Sistem Booking Layanan — Laravel

Sistem Booking Layanan adalah aplikasi berbasis web yang dibangun menggunakan **Laravel** untuk mengelola pemesanan layanan (booking) antara customer dan staff, lengkap dengan fitur slot waktu otomatis, manajemen status booking, hingga laporan grafik interaktif untuk admin.

Project ini cocok digunakan untuk:
- Barbershop / salon
- Klinik / tempat praktik
- Jasa service
- Dan berbagai layanan berbasis appointment

---

## 🚀 Fitur Utama

### 👤 Multi Role
- **Admin**: kelola data, booking, laporan grafik.
- **Staff**: melihat jadwal dan booking yang ditangani.
- **Customer**: membuat booking layanan.

---

### 📋 Sistem Booking Pintar
- Slot waktu otomatis sesuai:
  - jadwal staff
  - durasi layanan
  - booking lain yang sudah ada (tanpa bentrok)
- Staff otomatis terfilter berdasarkan layanan yang dipilih.
- Status booking:
  - `pending`
  - `confirmed`
  - `completed`
  - `cancelled`

---

### 📊 Dashboard Grafik (Admin)
- Grafik booking per hari
- Distribusi status booking
- Layanan terlaris
- KPI ringkasan (total booking, completion rate, dll)
- Filter periode tanggal

---

### 🌙 UI Modern
- Tema dark mode
- Responsive (mobile & desktop)
- Dibangun dengan Tailwind CSS + Laravel Breeze

---

## 🛠️ Teknologi yang Digunakan

- Laravel 10+
- PHP 8.1+
- MySQL / MariaDB
- TailwindCSS
- Laravel Breeze (Auth)
- Chart.js
- Flatpickr (Date picker)

---

## 📦 Cara Install (Step by Step)

### 1. Clone repository

```bash
git clone https://github.com/USERNAME/NAMA-REPO.git
cd NAMA-REPO

composer install
npm install

cp .env.example .env

DB_DATABASE=booking_system
DB_USERNAME=root
DB_PASSWORD=

php artisan key:generate

php artisan migrate --seed

php artisan serve
npm run dev