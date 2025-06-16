# 🏥 Rumah Sakit Modern - Sistem Informasi Manajemen

Sistem Informasi Manajemen Rumah Sakit berbasis web yang modern dan user-friendly, dibangun dengan PHP, MySQL, HTML, CSS, dan JavaScript. Sistem ini dirancang untuk memenuhi kebutuhan manajemen rumah sakit secara komprehensif.

## ✨ Fitur Utama

### 👥 Manajemen Pasien
- ✅ Registrasi pasien baru (rawat jalan & rawat inap)
- ✅ Pencatatan data lengkap pasien
- ✅ Tracking riwayat kunjungan
- ✅ Manajemen data demografis

### 📋 Rekam Medis Digital
- ✅ Input rekam medis elektronik
- ✅ Riwayat pemeriksaan lengkap
- ✅ Pencatatan vital signs (TB, BB, Tensi)
- ✅ Diagnosis dan keluhan
- ✅ Integrasi dengan data dokter

### 👨‍⚕️ Manajemen Dokter
- ✅ Database dokter dan spesialisasi
- ✅ Jadwal dan aktivitas dokter
- ✅ Tracking performa dokter
- ✅ Manajemen visite dokter

### 💊 Farmasi & Obat
- ✅ Inventori obat real-time
- ✅ Manajemen stok dan kadaluarsa
- ✅ Sistem resep digital
- ✅ Tracking penggunaan obat
- ✅ Alert stok rendah

### 🏥 Rawat Inap
- ✅ Manajemen kamar dan kelas
- ✅ Check-in/Check-out otomatis
- ✅ Perhitungan biaya otomatis
- ✅ Tracking lama rawat inap

### 🔧 Tindakan Medis
- ✅ Master data alat medis
- ✅ Pencatatan penggunaan alat
- ✅ Kalkulasi biaya tindakan
- ✅ Riwayat tindakan per pasien

### 📊 Laporan & Analisis
- ✅ Dashboard real-time
- ✅ Statistik operasional
- ✅ Laporan keuangan
- ✅ Analisis performa
- ✅ Export & cetak laporan

### 🖨️ Struk & Kwitansi
- ✅ Struk pembayaran otomatis
- ✅ Kwitansi rawat inap
- ✅ Format cetak profesional

## 🗄️ Struktur Database

Sistem menggunakan 13 tabel utama sesuai spesifikasi:

### Tabel Master:
- **pasien** - Data pasien (ID, nama, alamat, dll)
- **dokter** - Data dokter dan spesialisasi
- **jenis_keahlian** - Master spesialisasi dokter
- **obat** - Master data obat dan inventori
- **data_ruang** - Master ruang rawat inap
- **tindak_medis** - Master tindakan medis

### Tabel Transaksi:
- **rekam_medis** - Kartu status/rekam medis
- **detail_inap** - Detail rawat inap pasien
- **visite_dokter** - Tarif visite per kelas
- **resep_obat** - Header resep obat
- **detail_obat** - Detail resep obat
- **detail_tindak_medis** - Detail tindakan medis
- **struk_obat** - Struk pembayaran obat

## 🚀 Instalasi

### Persyaratan Sistem
- 🔧 **XAMPP** (Apache + MySQL + PHP 7.4+)
- 🌐 **Web Browser** modern (Chrome, Firefox, Edge)
- 💾 **Database MySQL** 5.7+

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
│   └── style.css                 # Stylesheet modern & responsive
├── db.php                        # Konfigurasi database + functions
├── index.php                     # Dashboard utama
├── pasien_form.php              # Form input pasien
├── pasien_list.php              # Daftar pasien
├── dokter_form.php              # Form input dokter
├── dokter_list.php              # Daftar dokter
├── obat_form.php                # Form input obat
├── obat_list.php                # Daftar obat
├── rekam_medis_form.php         # Form rekam medis
├── rekam_medis_list.php         # Daftar rekam medis
├── resep_form.php               # Form resep obat
├── resep_list.php               # Daftar resep
├── rawat_inap_form.php          # Form rawat inap
├── rawat_inap_list.php          # Daftar rawat inap
├── tindakan_medis_form.php      # Form tindakan medis
├── tindakan_medis_list.php      # Daftar tindakan medis
├── struk_cetak.php              # Struk pembayaran umum
├── struk_rawat_inap.php         # Struk rawat inap
├── laporan.php                  # Dashboard laporan
├── database_setup.sql           # Script database lengkap
└── README.md                    # Dokumentasi ini
```

## 🎨 Desain & UI/UX

### Modern & Responsive
- ✅ **Gradient backgrounds** dengan glassmorphism
- ✅ **Responsive design** untuk mobile & desktop
- ✅ **Card-based layout** yang clean
- ✅ **Icon-rich interface** dengan emoji & symbols
- ✅ **Smooth animations** dan transitions
- ✅ **Dark mode ready** styling

### User Experience
- ✅ **Intuitive navigation** dengan breadcrumbs
- ✅ **Real-time feedback** dengan alerts
- ✅ **Auto-calculation** untuk biaya
- ✅ **Search & filtering** capabilities
- ✅ **Print-friendly** layouts

## 🔐 Keamanan

- ✅ **SQL Injection protection** dengan prepared statements
- ✅ **Input sanitization** untuk semua user input
- ✅ **XSS prevention** dengan proper escaping
- ✅ **Session management** untuk user authentication
- ✅ **Data validation** client & server side

## 📊 Data Sample

Database dilengkapi dengan data sample untuk testing:
- 👨‍⚕️ **5 Dokter** dengan berbagai spesialisasi
- 👥 **5 Pasien** contoh dengan data lengkap
- 💊 **8 Jenis Obat** dengan stok dan harga
- 🏥 **4 Kelas Ruang** rawat inap
- 🔧 **6 Tindakan Medis** dengan tarif
- 📋 **Sample rekam medis** dan transaksi

## 🛠️ Teknologi

### Backend:
- **PHP 7.4+** - Server-side scripting
- **MySQL 5.7+** - Database management
- **MySQLi** - Database connectivity

### Frontend:
- **HTML5** - Semantic markup
- **CSS3** - Modern styling & animations
- **JavaScript ES6** - Interactive functionality
- **Responsive Design** - Mobile-first approach

### Features:
- **AJAX** - Asynchronous data loading
- **Print CSS** - Professional print layouts
- **Progressive Enhancement** - Graceful degradation

## 🎯 Target Pengguna

- 🏥 **Rumah Sakit** kecil hingga menengah
- 🏨 **Klinik** dan poliklinik
- 👨‍⚕️ **Praktik dokter** pribadi
- 🎓 **Institusi pendidikan** kedokteran
- 💼 **Developer** untuk referensi dan pengembangan

## 📈 Roadmap

### Phase 1 (Selesai) ✅
- [x] Database design & implementation
- [x] Basic CRUD operations
- [x] User interface & navigation
- [x] Report generation

### Phase 2 (Future) 🔄
- [ ] User authentication & authorization
- [ ] Advanced reporting & analytics
- [ ] API integration
- [ ] Mobile app companion

### Phase 3 (Future) 🚀
- [ ] Cloud deployment
- [ ] Multi-tenant support
- [ ] Advanced security features
- [ ] Integration with medical devices

## 🤝 Kontribusi

Proyek ini open untuk kontribusi! Silakan:
1. **Fork** repository
2. **Create feature branch**
3. **Commit changes**
4. **Push to branch**
5. **Create Pull Request**

## 📞 Support

Untuk pertanyaan, bug report, atau feature request:
- 📧 Email: admin@rumahsakitmodern.com
- 🐛 Issues: GitHub Issues
- 📖 Documentation: README.md

## 📄 Lisensi

Proyek ini menggunakan lisensi **MIT License**. Bebas digunakan untuk keperluan komersial maupun non-komersial.

---

**🏥 Rumah Sakit Modern - Kesehatan Digital untuk Masa Depan**

*"Inovasi teknologi untuk pelayanan kesehatan yang lebih baik"*
