# 🔬 Sistem Manajemen Laboratorium RPL

<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  <p><i>Powered by Laravel 11</i></p>
</div>

<div align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Laravel-11.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
</div>

## 📋 Deskripsi Aplikasi

> Aplikasi modern untuk mengelola laboratorium RPL dengan fitur lengkap dan antarmuka yang intuitif.

Sistem Manajemen Laboratorium RPL adalah solusi komprehensif berbasis web untuk pengelolaan laboratorium Rekayasa Perangkat Lunak. Dengan antarmuka yang responsif dan fitur yang kaya, aplikasi ini mempermudah manajemen inventaris, proses peminjaman, dan pengelolaan penggunaan laboratorium.

---

## ✨ Fitur Utama

<table>
  <tr>
    <td width="50%">
      <h3>📦 Manajemen Peralatan</h3>
      <ul>
        <li>Katalog peralatan digital</li>
        <li>Pelacakan real-time stok & kondisi</li>
        <li>Riwayat penggunaan komprehensif</li>
        <li>Kategorisasi & pencarian cepat</li>
      </ul>
    </td>
    <td width="50%">
      <h3>🔄 Peminjaman Barang</h3>
      <ul>
        <li>Antarmuka peminjaman yang intuitif</li>
        <li>Sistem persetujuan multi-level</li>
        <li>Notifikasi status peminjaman</li>
        <li>Pelacakan pengembalian</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td>
      <h3>🏛️ Peminjaman Laboratorium</h3>
      <ul>
        <li>Kalendar penjadwalan visual</li>
        <li>Deteksi konflik jadwal otomatis</li>
        <li>Pemesanan ruangan terjadwal</li>
        <li>Panel admin untuk persetujuan</li>
      </ul>
    </td>
    <td>
      <h3>👥 Manajemen Pengguna</h3>
      <ul>
        <li>Portal siswa & guru terintegrasi</li>
        <li>Manajemen hak akses berbasis peran</li>
        <li>Profil pengguna yang dapat disesuaikan</li>
        <li>Sistem autentikasi aman</li>
      </ul>
    </td>
  </tr>
</table>

---

## 🚀 Teknologi yang Digunakan

<div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
  <div style="flex: 1; min-width: 250px; margin: 10px; padding: 15px; background-color: #f8f9fa; border-radius: 10px;">
    <h3>🔧 Backend</h3>
    <ul>
      <li><strong>Laravel 11</strong> - Framework PHP modern</li>
      <li><strong>MySQL 8.0+</strong> - Database yang handal</li>
      <li><strong>Redis</strong> - Caching untuk performa optimal</li>
      <li><strong>API RESTful</strong> - Untuk integrasi dengan aplikasi lain</li>
    </ul>
  </div>
  
  <div style="flex: 1; min-width: 250px; margin: 10px; padding: 15px; background-color: #f8f9fa; border-radius: 10px;">
    <h3>🎨 Frontend</h3>
    <ul>
      <li><strong>Bootstrap 5</strong> - UI yang responsif</li>
      <li><strong>AlpineJS</strong> - Interaktivitas yang ringan</li>
      <li><strong>Livewire</strong> - Komponen dinamis</li>
      <li><strong>SweetAlert2</strong> - Notifikasi elegan</li>
    </ul>
  </div>
  
  <div style="flex: 1; min-width: 250px; margin: 10px; padding: 15px; background-color: #f8f9fa; border-radius: 10px;">
    <h3>📊 Reporting</h3>
    <ul>
      <li><strong>Chart.js</strong> - Visualisasi data interaktif</li>
      <li><strong>Maatwebsite/Excel</strong> - Export data ke Excel</li>
      <li><strong>DomPDF</strong> - Generasi laporan PDF</li>
      <li><strong>DataTables</strong> - Tabel data interaktif</li>
    </ul>
  </div>
</div>

---

## 💻 Instalasi & Pengaturan

### Persyaratan Sistem

-   PHP 8.2 atau lebih baru
-   Composer 2.0+
-   MySQL 8.0+
-   Node.js 18+ & NPM
-   Web server (Apache/Nginx)

### Langkah Instalasi Cepat

<div style="background-color: #f6f8fa; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
<pre style="margin: 0;">
<code>
# Clone repositori
git clone https://github.com/username/lab-management.git
cd lab-management

# Instal dependensi

composer install
npm install && npm run build

# Konfigurasi aplikasi

cp .env.example .env
php artisan key:generate
php artisan storage:link

# Setup database

php artisan migrate --seed

# Jalankan server

php artisan serve
</code>

</pre>
</div>

> 🌟 **Tip Pro**: Gunakan `php artisan migrate:fresh --seed` untuk mereset database dengan data dummy saat development.

---

## 📱 Panduan Pengguna

<div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px;">
  <div style="flex: 1; min-width: 300px; border: 1px solid #e1e4e8; border-radius: 10px; padding: 15px;">
    <h3 style="margin-top: 0;">🔑 Login & Autentikasi</h3>
    <ol>
      <li>Akses aplikasi di <code>http://localhost:8000</code></li>
      <li>Pilih bahasa (Indonesia/Inggris)</li>
      <li>Masukkan kredensial yang diberikan</li>
      <li>Untuk admin: <code>admin@example.com / password</code></li>
    </ol>
  </div>
  
  <div style="flex: 1; min-width: 300px; border: 1px solid #e1e4e8; border-radius: 10px; padding: 15px;">
    <h3 style="margin-top: 0;">📊 Dashboard</h3>
    <ol>
      <li>Lihat statistik real-time penggunaan lab</li>
      <li>Pantau peminjaman aktif</li>
      <li>Cek status ketersediaan peralatan</li>
      <li>Akses cepat ke fungsi-fungsi populer</li>
    </ol>
  </div>
</div>

<div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px;">
  <div style="flex: 1; min-width: 300px; border: 1px solid #e1e4e8; border-radius: 10px; padding: 15px;">
    <h3 style="margin-top: 0;">📝 Proses Peminjaman</h3>
    <ol>
      <li>Pilih kategori peminjaman (Barang/Lab)</li>
      <li>Cek ketersediaan pada kalendar interaktif</li>
      <li>Isi form peminjaman dengan detail</li>
      <li>Pantau status persetujuan di dashboard</li>
    </ol>
  </div>
  
  <div style="flex: 1; min-width: 300px; border: 1px solid #e1e4e8; border-radius: 10px; padding: 15px;">
    <h3 style="margin-top: 0;">👑 Panel Admin</h3>
    <ol>
      <li>Kelola semua peminjaman dari dashboard admin</li>
      <li>Tinjau dan setujui/tolak permintaan peminjaman</li>
      <li>Tambah/edit data peralatan dan laboratorium</li>
      <li>Generate laporan analitik detail</li>
    </ol>
  </div>
</div>

---

## 🛠️ Troubleshooting

<table>
  <tr>
    <th>Masalah</th>
    <th>Solusi</th>
  </tr>
  <tr>
    <td>Error saat migrasi database</td>
    <td>
      <ul>
        <li>Periksa kredensial database di <code>.env</code></li>
        <li>Pastikan versi MySQL 8.0+</li>
        <li>Coba <code>php artisan config:clear</code></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td>Halaman tidak memuat dengan benar</td>
    <td>
      <ul>
        <li>Jalankan <code>npm run build</code></li>
        <li>Bersihkan cache: <code>php artisan view:clear</code></li>
        <li>Periksa error di konsol browser</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td>Permission Denied pada Storage</td>
    <td>
      <ul>
        <li><code>chmod -R 775 storage bootstrap/cache</code></li>
        <li><code>chown -R www-data:www-data storage</code></li>
        <li>Periksa konfigurasi webserver</li>
      </ul>
    </td>
  </tr>
</table>

---

## 📞 Dukungan & Kontribusi

<div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 15px;">
  <div style="flex: 1; min-width: 250px; background-color: #f0f7ff; padding: 15px; border-radius: 10px;">
    <h3>🤝 Cara Berkontribusi</h3>
    <ol>
      <li>Fork repositori</li>
      <li>Buat branch untuk fitur Anda</li>
      <li>Kirim pull request dengan deskripsi detail</li>
      <li>Tunggu review dari maintainer</li>
    </ol>
  </div>
  
  <div style="flex: 1; min-width: 250px; background-color: #fff8f0; padding: 15px; border-radius: 10px;">
    <h3>💬 Bantuan & Dukungan</h3>
    <ul>
      <li>Laporkan isu di repositori GitHub</li>
      <li>Email dukungan: <a href="mailto:support@example.com">support@example.com</a></li>
      <li>Dokumentasi lengkap: <a href="#">docs.labmanagement.com</a></li>
      <li>FAQ: <a href="#">labmanagement.com/faq</a></li>
    </ul>
  </div>
</div>

---

<div align="center" style="margin-top: 30px; margin-bottom: 20px;">
  <h3>Dibuat dengan ❤️ oleh Tim Pengembang Laboratorium RPL</h3>
  <p>© 2023-2024 Laboratorium RPL. Seluruh hak cipta dilindungi.</p>
  
  <div style="margin-top: 15px;">
    <a href="#" style="text-decoration: none; margin: 0 10px;"><img src="https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white" height="25" alt="GitHub"></a>
    <a href="#" style="text-decoration: none; margin: 0 10px;"><img src="https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white" height="25" alt="LinkedIn"></a>
    <a href="#" style="text-decoration: none; margin: 0 10px;"><img src="https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube&logoColor=white" height="25" alt="YouTube"></a>
  </div>
</div>
