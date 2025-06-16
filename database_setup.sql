-- Database Schema untuk Rumah Sakit Modern
-- Jalankan script ini di phpMyAdmin atau MySQL client

-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS db_rumah_sakit;
USE db_rumah_sakit;

-- a) Tabel Data Pasien
CREATE TABLE IF NOT EXISTS pasien (
    Id_Pasien INT AUTO_INCREMENT PRIMARY KEY,
    Jenis_Pasien ENUM('Rawat Jalan', 'Rawat Inap') NOT NULL,
    Nm_Pasien VARCHAR(100) NOT NULL,
    Tgl_Masuk DATE NOT NULL,
    Tmpt_Lahir VARCHAR(50) NOT NULL,
    Tgl_lahir DATE NOT NULL,
    Umur INT NOT NULL,
    JK ENUM('L', 'P') NOT NULL,
    Alamat TEXT NOT NULL,
    Tlpn VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- d) Tabel Jenis Keahlian
CREATE TABLE IF NOT EXISTS jenis_keahlian (
    Kode_keahlian VARCHAR(10) PRIMARY KEY,
    Nmbidang_keahlian VARCHAR(100) NOT NULL
);

-- c) Tabel Data Dokter
CREATE TABLE IF NOT EXISTS dokter (
    id_dokter INT AUTO_INCREMENT PRIMARY KEY,
    nm_dokter VARCHAR(100) NOT NULL,
    JK ENUM('L', 'P') NOT NULL,
    status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif',
    tgl_lahir DATE NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    pendidikan VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    Kode_Keahlian VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Kode_Keahlian) REFERENCES jenis_keahlian(Kode_keahlian)
);

-- f) Tabel Data Ruang
CREATE TABLE IF NOT EXISTS data_ruang (
    Kelas VARCHAR(10) PRIMARY KEY,
    nm_Rinap VARCHAR(50) NOT NULL,
    Tarif DECIMAL(10,2) NOT NULL,
    Jum_RInap INT NOT NULL
);

-- e) Tabel Visite Dokter
CREATE TABLE IF NOT EXISTS visite_dokter (
    Id_dokter INT,
    kelas VARCHAR(10),
    Tarif_visite DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (Id_dokter, kelas),
    FOREIGN KEY (Id_dokter) REFERENCES dokter(id_dokter),
    FOREIGN KEY (kelas) REFERENCES data_ruang(Kelas)
);

-- h) Tabel Tindak Medis
CREATE TABLE IF NOT EXISTS tindak_medis (
    kode_tindakMedis VARCHAR(10) PRIMARY KEY,
    nm_alatmedik VARCHAR(100) NOT NULL,
    Jenis_medik VARCHAR(50) NOT NULL,
    jum_alatmedik INT NOT NULL,
    harga_alatMedik DECIMAL(10,2) NOT NULL
);

-- j) Tabel Obat
CREATE TABLE IF NOT EXISTS obat (
    Kode_obat VARCHAR(10) PRIMARY KEY,
    nm_obat VARCHAR(100) NOT NULL,
    harga_obat DECIMAL(10,2) NOT NULL,
    tgl_kadaluarsa DATE NOT NULL,
    satuan VARCHAR(20) NOT NULL,
    letak_obat VARCHAR(50) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- b) Tabel Data Kartu Status (Rekam Medis)
CREATE TABLE IF NOT EXISTS rekam_medis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_Pasien INT NOT NULL,
    tgl_periksa DATETIME DEFAULT CURRENT_TIMESTAMP,
    keluhan TEXT NOT NULL,
    diagnosa TEXT NOT NULL,
    TB DECIMAL(5,2), -- Tinggi Badan
    BB DECIMAL(5,2), -- Berat Badan
    Tensi_darah VARCHAR(20), -- Tekanan darah
    id_dokter INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_Pasien) REFERENCES pasien(Id_Pasien) ON DELETE CASCADE,
    FOREIGN KEY (id_dokter) REFERENCES dokter(id_dokter) ON DELETE CASCADE
);

-- g) Tabel Detail Inap
CREATE TABLE IF NOT EXISTS detail_inap (
    Id_Pasien INT,
    kelas VARCHAR(10),
    tgl_inap DATE NOT NULL,
    Tgl_keluarinap DATE,
    total_Inap DECIMAL(12,2),
    lama_inap INT,
    PRIMARY KEY (Id_Pasien, kelas, tgl_inap),
    FOREIGN KEY (Id_Pasien) REFERENCES pasien(Id_Pasien),
    FOREIGN KEY (kelas) REFERENCES data_ruang(Kelas)
);

-- i) Tabel Detail Tindak Medis
CREATE TABLE IF NOT EXISTS detail_tindak_medis (
    Id_Pasien INT,
    Kode_tindakMedis VARCHAR(10),
    Jum_alatmedik INT NOT NULL,
    PRIMARY KEY (Id_Pasien, Kode_tindakMedis),
    FOREIGN KEY (Id_Pasien) REFERENCES pasien(Id_Pasien),
    FOREIGN KEY (Kode_tindakMedis) REFERENCES tindak_medis(kode_tindakMedis)
);

-- l) Tabel Resep Obat
CREATE TABLE IF NOT EXISTS resep_obat (
    Kode_Resep VARCHAR(10) PRIMARY KEY,
    Id_Pasien INT NOT NULL,
    tgl_resep DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Id_Pasien) REFERENCES pasien(Id_Pasien)
);

-- k) Tabel Detail Obat
CREATE TABLE IF NOT EXISTS detail_obat (
    Kode_Resep VARCHAR(10),
    kode_obat VARCHAR(10),
    Juml_obat INT NOT NULL,
    aturan_Pakai TEXT NOT NULL,
    id_Pasien INT,
    PRIMARY KEY (Kode_Resep, kode_obat),
    FOREIGN KEY (Kode_Resep) REFERENCES resep_obat(Kode_Resep),
    FOREIGN KEY (kode_obat) REFERENCES obat(Kode_obat),
    FOREIGN KEY (id_Pasien) REFERENCES pasien(Id_Pasien)
);

-- m) Tabel Struk Obat
CREATE TABLE IF NOT EXISTS struk_obat (
    No_Struk VARCHAR(15) PRIMARY KEY,
    tgl_pembayaran DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_bayar DECIMAL(12,2) NOT NULL,
    Kode_resep VARCHAR(10),
    FOREIGN KEY (Kode_resep) REFERENCES resep_obat(Kode_Resep)
);

-- Insert data sample jenis keahlian
INSERT INTO jenis_keahlian (Kode_keahlian, Nmbidang_keahlian) VALUES
('DOC001', 'Dokter Umum'),
('DOC002', 'Spesialis Anak'),
('DOC003', 'Spesialis Jantung'),
('DOC004', 'Spesialis Kandungan'),
('DOC005', 'Spesialis Mata'),
('DOC006', 'Spesialis Kulit'),
('DOC007', 'Spesialis Bedah'),
('DOC008', 'Spesialis Penyakit Dalam');

-- Insert data sample dokter
INSERT INTO dokter (nm_dokter, JK, status, tgl_lahir, tempat_lahir, pendidikan, alamat, Kode_Keahlian) VALUES
('Ahmad Santoso', 'L', 'Aktif', '1980-05-15', 'Jakarta', 'S1 Kedokteran UI', 'Jl. Merdeka No. 123, Jakarta', 'DOC001'),
('Sari Dewi', 'P', 'Aktif', '1985-08-22', 'Bandung', 'S1 Kedokteran UNPAD', 'Jl. Sudirman No. 456, Bandung', 'DOC002'),
('Budi Hartono', 'L', 'Aktif', '1978-12-10', 'Surabaya', 'S1 Kedokteran UNAIR', 'Jl. Thamrin No. 789, Surabaya', 'DOC003'),
('Maya Sari', 'P', 'Aktif', '1982-03-18', 'Yogyakarta', 'S1 Kedokteran UGM', 'Jl. Malioboro No. 321, Yogyakarta', 'DOC004'),
('Rudi Prasetyo', 'L', 'Aktif', '1979-07-25', 'Medan', 'S1 Kedokteran USU', 'Jl. Kuningan No. 654, Medan', 'DOC005');

-- Insert data sample ruang
INSERT INTO data_ruang (Kelas, nm_Rinap, Tarif, Jum_RInap) VALUES
('VIP', 'Ruang VIP', 500000.00, 10),
('I', 'Kelas 1', 300000.00, 20),
('II', 'Kelas 2', 200000.00, 30),
('III', 'Kelas 3', 100000.00, 40);

-- Insert data sample visite dokter
INSERT INTO visite_dokter (Id_dokter, kelas, Tarif_visite) VALUES
(1, 'VIP', 200000.00),
(1, 'I', 150000.00),
(1, 'II', 100000.00),
(1, 'III', 75000.00),
(2, 'VIP', 250000.00),
(2, 'I', 200000.00),
(3, 'VIP', 300000.00),
(3, 'I', 250000.00);

-- Insert data sample tindak medis
INSERT INTO tindak_medis (kode_tindakMedis, nm_alatmedik, Jenis_medik, jum_alatmedik, harga_alatMedik) VALUES
('TM001', 'Stetoskop', 'Pemeriksaan', 5, 25000.00),
('TM002', 'Termometer Digital', 'Pemeriksaan', 10, 15000.00),
('TM003', 'Tensimeter', 'Pemeriksaan', 8, 30000.00),
('TM004', 'EKG', 'Diagnostik', 2, 200000.00),
('TM005', 'USG', 'Diagnostik', 1, 150000.00),
('TM006', 'Rontgen', 'Diagnostik', 1, 100000.00);

-- Insert data sample obat
INSERT INTO obat (Kode_obat, nm_obat, harga_obat, tgl_kadaluarsa, satuan, letak_obat, stok) VALUES
('OBT001', 'Paracetamol 500mg', 500.00, '2026-12-31', 'Tablet', 'Rak A1', 100),
('OBT002', 'Amoxicillin 500mg', 1200.00, '2026-06-30', 'Kapsul', 'Rak A2', 75),
('OBT003', 'OBH Combi', 15000.00, '2025-12-31', 'Botol', 'Rak B1', 50),
('OBT004', 'Betadine Solution', 25000.00, '2027-03-15', 'Botol', 'Rak B2', 30),
('OBT005', 'Vitamin C 1000mg', 300.00, '2026-09-30', 'Tablet', 'Rak C1', 200),
('OBT006', 'Antasida', 800.00, '2026-08-15', 'Tablet', 'Rak C2', 80),
('OBT007', 'Salep Acyclovir', 18000.00, '2025-11-30', 'Tube', 'Rak D1', 25),
('OBT008', 'Captopril 25mg', 1000.00, '2026-10-31', 'Tablet', 'Rak D2', 60);

-- Insert data sample pasien
INSERT INTO pasien (Jenis_Pasien, Nm_Pasien, Tgl_Masuk, Tmpt_Lahir, Tgl_lahir, Umur, JK, Alamat, Tlpn) VALUES
('Rawat Jalan', 'John Doe', '2025-06-15', 'Jakarta', '1985-05-15', 40, 'L', 'Jl. Merdeka No. 123, Jakarta', '08123456001'),
('Rawat Jalan', 'Jane Smith', '2025-06-16', 'Bandung', '1990-08-22', 34, 'P', 'Jl. Sudirman No. 456, Bandung', '08123456002'),
('Rawat Inap', 'Michael Johnson', '2025-06-17', 'Surabaya', '1988-12-10', 36, 'L', 'Jl. Thamrin No. 789, Surabaya', '08123456003'),
('Rawat Jalan', 'Sarah Williams', '2025-06-17', 'Yogyakarta', '1992-03-18', 33, 'P', 'Jl. Malioboro No. 321, Yogyakarta', '08123456004'),
('Rawat Inap', 'David Brown', '2025-06-17', 'Medan', '1987-07-25', 37, 'L', 'Jl. Kuningan No. 654, Medan', '08123456005');

-- Insert data sample rekam medis
INSERT INTO rekam_medis (id_Pasien, tgl_periksa, keluhan, diagnosa, TB, BB, Tensi_darah, id_dokter) VALUES
(1, '2025-06-15 09:00:00', 'Demam dan batuk sudah 3 hari', 'ISPA (Infeksi Saluran Pernapasan Atas)', 170.0, 65.5, '120/80', 1),
(2, '2025-06-16 10:30:00', 'Nyeri perut dan mual', 'Gastritis', 160.0, 55.0, '110/70', 1),
(3, '2025-06-17 14:00:00', 'Nyeri dada dan sesak napas', 'Angina Pectoris', 175.0, 80.0, '140/90', 3),
(4, '2025-06-17 15:30:00', 'Kontrol kehamilan rutin', 'Kehamilan Normal 20 minggu', 165.0, 58.0, '115/75', 4),
(5, '2025-06-17 16:45:00', 'Mata merah dan gatal', 'Konjungtivitis', 168.0, 70.0, '125/85', 5);

-- Insert data sample resep obat
INSERT INTO resep_obat (Kode_Resep, Id_Pasien, tgl_resep) VALUES
('RSP001', 1, '2025-06-15 09:30:00'),
('RSP002', 2, '2025-06-16 11:00:00'),
('RSP003', 3, '2025-06-17 14:30:00'),
('RSP004', 5, '2025-06-17 17:00:00');

-- Insert data sample detail obat
INSERT INTO detail_obat (Kode_Resep, kode_obat, Juml_obat, aturan_Pakai, id_Pasien) VALUES
('RSP001', 'OBT001', 10, '3x1 tablet setelah makan', 1),
('RSP001', 'OBT003', 1, '3x1 sendok makan', 1),
('RSP002', 'OBT006', 6, '2x1 tablet sebelum makan', 2),
('RSP003', 'OBT008', 14, '1x1 tablet pagi hari', 3),
('RSP004', 'OBT004', 1, 'Oleskan 2x sehari pada mata yang sakit', 5);

-- Insert data sample detail inap
INSERT INTO detail_inap (Id_Pasien, kelas, tgl_inap, Tgl_keluarinap, total_Inap, lama_inap) VALUES
(3, 'I', '2025-06-17', NULL, NULL, NULL),
(5, 'II', '2025-06-17', NULL, NULL, NULL);

-- Insert data sample detail tindak medis
INSERT INTO detail_tindak_medis (Id_Pasien, Kode_tindakMedis, Jum_alatmedik) VALUES
(1, 'TM001', 1),
(1, 'TM002', 1),
(2, 'TM001', 1),
(3, 'TM001', 1),
(3, 'TM003', 1),
(3, 'TM004', 1),
(4, 'TM005', 1),
(5, 'TM001', 1);

-- Insert data sample struk obat
INSERT INTO struk_obat (No_Struk, tgl_pembayaran, total_bayar, Kode_resep) VALUES
('STR001', '2025-06-15 10:00:00', 20000.00, 'RSP001'),
('STR002', '2025-06-16 11:30:00', 4800.00, 'RSP002'),
('STR003', '2025-06-17 15:00:00', 14000.00, 'RSP003'),
('STR004', '2025-06-17 17:30:00', 25000.00, 'RSP004');

-- Create indexes for better performance
CREATE INDEX idx_pasien_nama ON pasien(Nm_Pasien);
CREATE INDEX idx_dokter_keahlian ON dokter(Kode_Keahlian);
CREATE INDEX idx_obat_nama ON obat(nm_obat);
CREATE INDEX idx_rekam_medis_tanggal ON rekam_medis(tgl_periksa);
CREATE INDEX idx_rekam_medis_pasien ON rekam_medis(id_Pasien);
CREATE INDEX idx_rekam_medis_dokter ON rekam_medis(id_dokter);

-- Create views for reporting
CREATE VIEW view_rekam_medis_lengkap AS
SELECT 
    rm.id,
    rm.tgl_periksa,
    p.Nm_Pasien as nama_pasien,
    p.JK as jenis_kelamin,
    p.Umur,
    d.nm_dokter as nama_dokter,
    jk.Nmbidang_keahlian as spesialis,
    rm.keluhan,
    rm.diagnosa,
    rm.TB,
    rm.BB,
    rm.Tensi_darah
FROM rekam_medis rm
JOIN pasien p ON rm.id_Pasien = p.Id_Pasien
JOIN dokter d ON rm.id_dokter = d.id_dokter
LEFT JOIN jenis_keahlian jk ON d.Kode_Keahlian = jk.Kode_keahlian
ORDER BY rm.tgl_periksa DESC;

-- View untuk laporan obat
CREATE VIEW view_stok_obat AS
SELECT 
    o.Kode_obat,
    o.nm_obat,
    o.stok,
    o.satuan,
    o.harga_obat,
    o.tgl_kadaluarsa,
    o.letak_obat,
    CASE 
        WHEN o.stok <= 0 THEN 'Habis'
        WHEN o.stok < 10 THEN 'Stok Rendah'
        ELSE 'Tersedia'
    END as status_stok,
    CASE 
        WHEN o.tgl_kadaluarsa <= CURDATE() THEN 'Kadaluarsa'
        WHEN o.tgl_kadaluarsa <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'Akan Kadaluarsa'
        ELSE 'Aman'
    END as status_kadaluarsa
FROM obat o
ORDER BY o.nm_obat;

-- Success message
SELECT 'Database Rumah Sakit berhasil dibuat dengan struktur baru!' as message;
