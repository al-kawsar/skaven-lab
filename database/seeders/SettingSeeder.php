<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // General Institution Settings
        $this->createSetting('institution_name', 'SEKOLAH MENENGAH KEJURUAN NEGERI 7 MAKASSAR', 'institution', 'text', 'Nama Institusi');
        $this->createSetting('institution_code', 'SMKN7-MKS', 'institution', 'text', 'Kode Institusi');
        $this->createSetting('contact_email', 'info@smkn7-makassar.sch.id', 'institution', 'email', 'Email Kontak');
        $this->createSetting('contact_phone', '(0411) 123456', 'institution', 'text', 'Nomor Telepon');
        $this->createSetting('address', 'Jl. Andi Mappaoddang No. 27, Makassar, Sulawesi Selatan', 'institution', 'textarea', 'Alamat');
        $this->createSetting('logo', 'assets/img/smkn7.png', 'institution', 'file', 'Logo Institusi');
        $this->createSetting('favicon', 'assets/images/favicon.ico', 'institution', 'file', 'Favicon');
        $this->createSetting('footer_text', 'Sistem Manajemen Peminjaman Laboratorium © '.date('Y'), 'institution', 'text', 'Teks Footer');
        
        // Time & Format Settings
        $this->createSetting('timezone', 'Asia/Makassar', 'system', 'select', 'Zona Waktu', ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura']);
        $this->createSetting('date_format', 'd/m/Y', 'system', 'select', 'Format Tanggal', ['d/m/Y', 'Y-m-d', 'd-m-Y', 'j F Y']);
        $this->createSetting('time_format', 'WITA', 'system', 'select', 'Format Waktu', ['WIB', 'WITA', 'WIT']);
        
        // Lab Settings
        $this->createSetting('lab_name', 'Laboratorium RPL', 'lab', 'text', 'Nama Laboratorium');
        $this->createSetting('lab_code', 'LAB-RPL', 'lab', 'text', 'Kode Laboratorium');
        $this->createSetting('lab_location', 'Gedung Utama Lantai 2', 'lab', 'text', 'Lokasi Laboratorium');
        $this->createSetting('lab_description', 'Laboratorium Rekayasa Perangkat Lunak untuk praktikum pemrograman dan pengembangan aplikasi', 'lab', 'textarea', 'Deskripsi Laboratorium');
        $this->createSetting('operation_start', '08:00', 'lab', 'time', 'Jam Operasional Mulai');
        $this->createSetting('operation_end', '16:00', 'lab', 'time', 'Jam Operasional Selesai');
        $this->createSetting('max_booking_days_ahead', '14', 'lab', 'number', 'Maksimal Hari Pemesanan di Muka');
        $this->createSetting('require_approval', 'true', 'lab', 'boolean', 'Perlu Persetujuan Admin');
        $this->createSetting('enable_weekend_bookings', 'false', 'lab', 'boolean', 'Izinkan Peminjaman Akhir Pekan');
        
        // Print Settings
        $this->createSetting('lab_head_name', 'Drs. Nama Kepala Lab', 'print', 'text', 'Nama Kepala Laboratorium');
        $this->createSetting('lab_head_title', 'Kepala Laboratorium', 'print', 'text', 'Jabatan Kepala Laboratorium');
        $this->createSetting('lab_head_nip', '198101012010011001', 'print', 'text', 'NIP Kepala Laboratorium');
        $this->createSetting('school_name', 'SEKOLAH MENENGAH KEJURUAN NEGERI 7 MAKASSAR', 'print', 'text', 'Nama Sekolah pada Cetakan');
        $this->createSetting('school_address', 'Jl. Andi Mappaoddang No. 27, Makassar, Sulawesi Selatan', 'print', 'textarea', 'Alamat Sekolah pada Cetakan');
        $this->createSetting('school_postal_code', '90223', 'print', 'text', 'Kode Pos pada Cetakan');
        $this->createSetting('school_phone', '(0411) 123456', 'print', 'text', 'Telepon pada Cetakan');
        $this->createSetting('school_email', 'info@smkn7-makassar.sch.id', 'print', 'email', 'Email pada Cetakan');
        $this->createSetting('school_website', 'www.smkn7-makassar.sch.id', 'print', 'url', 'Website pada Cetakan');
        $this->createSetting('borrowing_terms', "Peminjam bertanggung jawab atas kebersihan dan keutuhan lab selama penggunaan\nKerusakan akibat kelalaian peminjam menjadi tanggung jawab peminjam\nPeminjam wajib mematikan semua peralatan elektronik setelah selesai\nDokumen ini wajib dibawa saat menggunakan lab\nDalam kondisi darurat, pihak sekolah berhak membatalkan peminjaman", 'print', 'textarea', 'Ketentuan Peminjaman');
        $this->createSetting('print_footer_text', 'Sistem Manajemen Peminjaman Laboratorium SMK Negeri 7 Makassar', 'print', 'text', 'Teks Footer Cetak');
        
        // Security Settings
        $this->createSetting('min_password_length', '8', 'security', 'number', 'Minimal Panjang Password');
        $this->createSetting('require_special_char', 'true', 'security', 'boolean', 'Wajib Karakter Khusus pada Password');
        $this->createSetting('require_number', 'true', 'security', 'boolean', 'Wajib Angka pada Password');
        $this->createSetting('require_uppercase', 'true', 'security', 'boolean', 'Wajib Huruf Kapital pada Password');
        $this->createSetting('max_login_attempts', '5', 'security', 'number', 'Maksimal Percobaan Login');
        $this->createSetting('student_can_borrow_equipment', 'false', 'security', 'boolean', 'Siswa Dapat Meminjam Peralatan');
        $this->createSetting('student_can_book_lab', 'false', 'security', 'boolean', 'Siswa Dapat Memesan Lab');
        $this->createSetting('require_student_id', 'true', 'security', 'boolean', 'Wajib ID Siswa');
        
        // Notification Settings
        $this->createSetting('enable_email_notifications', 'true', 'notification', 'boolean', 'Aktifkan Notifikasi Email');
        $this->createSetting('notify_admin_on_new_booking', 'true', 'notification', 'boolean', 'Notifikasi Admin pada Pemesanan Baru');
        $this->createSetting('notify_late_returns', 'true', 'notification', 'boolean', 'Notifikasi Keterlambatan Pengembalian');
        $this->createSetting('return_reminder_hours', '2', 'notification', 'number', 'Jam Pengingat Pengembalian');
    }
    
    /**
     * Helper method to create a setting
     */
    private function createSetting($key, $value, $group, $type, $label, $options = null)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
                'label' => $label,
                'options' => $options ? json_encode($options) : null
            ]
        );
    }
}
