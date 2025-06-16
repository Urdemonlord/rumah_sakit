-- Database Schema untuk Rumah Sakit Modern
-- Jalankan script ini di phpMyAdmin atau MySQL client

-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS db_rumah_sakit;
USE db_rumah_sakit;

-- Tabel Pasien
CREATE TABLE IF NOT EXISTS pasien (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    no_ktp VARCHAR(16) UNIQUE,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan'),
    tgl_lahir DATE NOT NULL,
    alamat TEXT NOT NULL,
    no_telepon VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Dokter
CREATE TABLE IF NOT EXISTS dokter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    spesialis VARCHAR(100) NOT NULL,
    no_sip VARCHAR(50),
    no_telepon VARCHAR(15),
    alamat TEXT,
    status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Obat
CREATE TABLE IF NOT EXISTS obat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_obat VARCHAR(100) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    satuan VARCHAR(20) DEFAULT 'Pcs',
    harga DECIMAL(10,2) DEFAULT 0.00,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Rekam Medis
CREATE TABLE IF NOT EXISTS rekam_medis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pasien INT NOT NULL,
    id_dokter INT NOT NULL,
    keluhan TEXT NOT NULL,
    diagnosa TEXT NOT NULL,
    tindakan TEXT NOT NULL,
    obat TEXT,
    tanggal_periksa DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pasien) REFERENCES pasien(id) ON DELETE CASCADE,
    FOREIGN KEY (id_dokter) REFERENCES dokter(id) ON DELETE CASCADE
);

-- Insert data sample dokter
INSERT INTO dokter (nama, spesialis, no_sip, no_telepon) VALUES
('Ahmad Santoso', 'Dokter Umum', 'SIP001/2023', '08123456789'),
('Sari Dewi', 'Spesialis Anak', 'SIP002/2023', '08123456790'),
('Budi Hartono', 'Spesialis Jantung', 'SIP003/2023', '08123456791'),
('Maya Sari', 'Spesialis Kandungan', 'SIP004/2023', '08123456792'),
('Rudi Prasetyo', 'Spesialis Mata', 'SIP005/2023', '08123456793'),
('Lisa Wijaya', 'Spesialis Kulit', 'SIP006/2023', '08123456794');

-- Insert data sample obat
INSERT INTO obat (nama_obat, stok, satuan, harga, keterangan) VALUES
('Paracetamol 500mg', 100, 'Tablet', 500.00, 'Obat penurun panas dan pereda nyeri'),
('Amoxicillin 500mg', 75, 'Kapsul', 1200.00, 'Antibiotik untuk infeksi bakteri'),
('OBH Combi', 50, 'Botol', 15000.00, 'Obat batuk dan flu'),
('Betadine Solution', 30, 'Botol', 25000.00, 'Antiseptik untuk luka'),
('Vitamin C 1000mg', 200, 'Tablet', 300.00, 'Suplemen vitamin C'),
('Antasida', 80, 'Tablet', 800.00, 'Obat maag dan asam lambung'),
('Salep Acyclovir', 25, 'Tube', 18000.00, 'Obat herpes dan cacar air'),
('Captopril 25mg', 60, 'Tablet', 1000.00, 'Obat darah tinggi'),
('Metformin 500mg', 45, 'Tablet', 1500.00, 'Obat diabetes'),
('Ibuprofen 400mg', 90, 'Tablet', 800.00, 'Anti inflamasi dan pereda nyeri');

-- Insert data sample pasien
INSERT INTO pasien (nama, no_ktp, jenis_kelamin, tgl_lahir, alamat, no_telepon) VALUES
('John Doe', '3201234567890001', 'Laki-laki', '1985-05-15', 'Jl. Merdeka No. 123, Jakarta', '08123456001'),
('Jane Smith', '3201234567890002', 'Perempuan', '1990-08-22', 'Jl. Sudirman No. 456, Jakarta', '08123456002'),
('Michael Johnson', '3201234567890003', 'Laki-laki', '1988-12-10', 'Jl. Thamrin No. 789, Jakarta', '08123456003'),
('Sarah Williams', '3201234567890004', 'Perempuan', '1992-03-18', 'Jl. Gatot Subroto No. 321, Jakarta', '08123456004'),
('David Brown', '3201234567890005', 'Laki-laki', '1987-07-25', 'Jl. Kuningan No. 654, Jakarta', '08123456005');

-- Create indexes for better performance
CREATE INDEX idx_pasien_nama ON pasien(nama);
CREATE INDEX idx_dokter_spesialis ON dokter(spesialis);
CREATE INDEX idx_obat_nama ON obat(nama_obat);
CREATE INDEX idx_rekam_medis_tanggal ON rekam_medis(tanggal_periksa);
CREATE INDEX idx_rekam_medis_pasien ON rekam_medis(id_pasien);
CREATE INDEX idx_rekam_medis_dokter ON rekam_medis(id_dokter);

-- Create views for reporting
CREATE VIEW view_rekam_medis_lengkap AS
SELECT 
    rm.id,
    rm.tanggal_periksa,
    p.nama as nama_pasien,
    p.no_ktp,
    p.jenis_kelamin,
    p.tgl_lahir,
    TIMESTAMPDIFF(YEAR, p.tgl_lahir, CURDATE()) as umur,
    d.nama as nama_dokter,
    d.spesialis,
    rm.keluhan,
    rm.diagnosa,
    rm.tindakan,
    rm.obat
FROM rekam_medis rm
JOIN pasien p ON rm.id_pasien = p.id
JOIN dokter d ON rm.id_dokter = d.id
ORDER BY rm.tanggal_periksa DESC;

-- Success message
SELECT 'Database Rumah Sakit berhasil dibuat!' as message;
