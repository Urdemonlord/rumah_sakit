# 🏥 Rumah Sakit Modern - Sistem Informasi Manajemen

Sistem informasi manajemen rumah sakit berbasis web yang modern dan responsif, dibangun menggunakan PHP, MySQL, dan CSS modern.

## ✨ Fitur Utama

### 👥 Manajemen Pasien
- Input data pasien lengkap dengan KTP, jenis kelamin, dan kontak
- Daftar pasien dengan pencarian dan filter
- Profil pasien yang detail

### 📋 Rekam Medis
- Input rekam medis dengan keluhan, diagnosa, dan tindakan
- Riwayat rekam medis pasien
- Integrasi dengan data dokter dan pasien
- Pencatatan obat yang diberikan

### 👨‍⚕️ Manajemen Dokter
- Daftar dokter dan spesialisasi
- Informasi lengkap dokter
- Status aktif/tidak aktif

### 💊 Inventori Obat
- Manajemen stok obat
- Harga dan satuan obat
- Peringatan stok rendah
- Update stok real-time

### 🧾 Laporan & Cetak
- Struk pembayaran profesional
- Kwitansi untuk pasien
- Format cetak yang rapi

## 🚀 Instalasi

### Persyaratan Sistem
- XAMPP (Apache, MySQL, PHP 7.4+)
- Web browser modern
- Minimum 100MB ruang disk

### Langkah Instalasi

1. **Persiapan Folder**
   ```
   Copy folder 'rumah_sakit' ke: c:\xampp\htdocs\
   ```

2. **Setup Database**
   - Buka phpMyAdmin (http://localhost/phpmyadmin)
   - Import file `database_setup.sql`
   - Atau jalankan script SQL secara manual

3. **Konfigurasi Database**
   - Buka file `db.php`
   - Sesuaikan konfigurasi koneksi database jika diperlukan:
   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "db_rumah_sakit";
   ```

4. **Akses Aplikasi**
   - Buka browser
   - Akses: http://localhost/rumah_sakit/

## 📁 Struktur Folder

```
rumah_sakit/
├── assets/
│   └── style.css              # Stylesheet modern
├── db.php                     # Konfigurasi database
├── index.php                  # Dashboard utama
├── pasien_form.php           # Form input pasien
├── pasien_list.php           # Daftar pasien
├── dokter_list.php           # Daftar dokter
├── obat_form.php             # Form input obat
├── obat_list.php             # Daftar obat
├── rekam_medis_form.php      # Form rekam medis
├── rekam_medis_list.php      # Daftar rekam medis
├── struk_cetak.php           # Struk pembayaran
├── database_setup.sql        # Script database
└── README.md                 # Dokumentasi ini
```

## 🎨 Desain Modern

### Fitur UI/UX
- **Responsive Design**: Optimal di desktop, tablet, dan mobile
- **Gradient Background**: Warna modern dengan efek blur
- **Card-based Layout**: Interface yang clean dan terorganisir
- **Icon Integration**: Emoji dan icon untuk navigasi intuitif
- **Color Coding**: Status dengan warna untuk mudah dibaca
- **Print Friendly**: Layout khusus untuk pencetakan

### Teknologi Frontend
- CSS3 dengan Flexbox & Grid
- Backdrop filter untuk efek kaca
- Smooth transitions dan hover effects
- Modern typography menggunakan Segoe UI
- Responsive breakpoints

## 🔧 Fitur Teknis

### Keamanan
- Prepared statements untuk mencegah SQL injection
- Input validation dan sanitization
- Error handling yang proper

### Performance
- Optimized database queries
- Efficient CSS loading
- Minimal JavaScript untuk interaktivity

### Database
- Relational database design
- Foreign key constraints
- Indexed columns untuk performance
- Database views untuk reporting

## 📊 Data Sample

Aplikasi sudah dilengkapi dengan data sample:
- 6 Dokter dengan berbagai spesialisasi
- 5 Pasien dengan data lengkap
- 10 Jenis obat dengan stok dan harga
- Database indexes untuk performa optimal

## 🎯 Penggunaan

### Dashboard
- Akses menu utama dari dashboard
- Grid layout untuk navigasi mudah
- Statistik ringkas sistem

### Input Data Pasien
1. Masuk ke "Input Data Pasien"
2. Isi form lengkap (nama, KTP, alamat, dll)
3. Klik "Simpan Data Pasien"

### Rekam Medis
1. Pilih "Input Rekam Medis"
2. Pilih pasien dan dokter dari dropdown
3. Isi keluhan, diagnosa, tindakan
4. Tambahkan obat jika diperlukan
5. Simpan rekam medis

### Manajemen Obat
1. Tambah obat baru dengan harga dan stok
2. Monitor stok rendah dari daftar obat
3. Update stok langsung dari interface

### Cetak Struk
1. Akses dari daftar rekam medis
2. Klik "Cetak" pada rekam medis tertentu
3. Struk akan ditampilkan dengan format profesional
4. Gunakan fungsi print browser

## 🔄 Maintenance

### Backup Database
```sql
mysqldump -u root -p db_rumah_sakit > backup_rumah_sakit.sql
```

### Update Stok Obat
- Gunakan fitur update stok di daftar obat
- Monitor notifikasi stok rendah
- Lakukan restock secara berkala

### Monitoring
- Cek log error Apache/PHP
- Monitor performa database
- Backup data secara berkala

## 🚨 Troubleshooting

### Error Koneksi Database
- Pastikan MySQL service berjalan
- Cek konfigurasi di `db.php`
- Verifikasi nama database dan user

### CSS Tidak Muncul
- Cek path file `assets/style.css`
- Clear browser cache
- Pastikan tidak ada error 404

### Fitur Print Tidak Bekerja
- Gunakan browser modern (Chrome, Firefox, Edge)
- Allow pop-ups untuk domain localhost
- Cek printer settings

## 📧 Support

Untuk pertanyaan atau masalah:
- Cek dokumentasi ini terlebih dahulu
- Periksa log error di browser developer tools
- Pastikan semua file sudah di-upload dengan benar

## 🔮 Pengembangan Lanjutan

Fitur yang bisa ditambahkan:
- User authentication & authorization
- Appointment scheduling
- Medical imaging integration
- Reporting & analytics
- API for mobile integration
- Multi-language support

---

**Dibuat dengan ❤️ untuk meningkatkan pelayanan kesehatan yang lebih baik**
