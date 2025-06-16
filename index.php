<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Rumah Sakit Modern</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Sistem Informasi Manajemen Rumah Sakit Terintegrasi</p>
        </div>
        
        <div class="card">
            <h2>📊 Dashboard Utama</h2>
            <p>Selamat datang di sistem informasi rumah sakit. Pilih menu di bawah untuk mulai bekerja.</p>
            
            <div class="dashboard-grid">
                <div class="dashboard-item">
                    <div class="icon">👥</div>
                    <h3>Manajemen Pasien</h3>
                    <a href="pasien_form.php" class="btn btn-primary">➕ Input Data Pasien</a>
                    <a href="pasien_list.php" class="btn btn-success">📝 Daftar Pasien</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">📋</div>
                    <h3>Rekam Medis</h3>
                    <a href="rekam_medis_form.php" class="btn btn-primary">➕ Input Rekam Medis</a>
                    <a href="rekam_medis_list.php" class="btn btn-success">📋 Daftar Rekam Medis</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">👨‍⚕️</div>
                    <h3>Data Dokter</h3>
                    <a href="dokter_list.php" class="btn btn-success">👨‍⚕️ Daftar Dokter</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">💊</div>
                    <h3>Manajemen Obat</h3>
                    <a href="obat_form.php" class="btn btn-primary">➕ Tambah Obat</a>
                    <a href="obat_list.php" class="btn btn-success">💊 Daftar Obat</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">🧾</div>
                    <h3>Laporan & Cetak</h3>
                    <a href="struk_cetak.php" class="btn btn-primary">🖨️ Cetak Struk</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>