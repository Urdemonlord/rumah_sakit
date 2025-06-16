<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rumah Sakit Modern</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏥</text></svg>">
</head>
<body>
    <?php 
    include 'db.php';
    
    // Get statistics
    $total_pasien = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM pasien"))[0] ?? 0;
    $total_dokter = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM dokter"))[0] ?? 0;
    $total_obat = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM obat"))[0] ?? 0;
    $total_rekam_medis = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM rekam_medis"))[0] ?? 0;
    $stok_rendah = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM obat WHERE stok < 10"))[0] ?? 0;
    $pasien_hari_ini = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM rekam_medis WHERE DATE(tgl_periksa) = CURDATE()"))[0] ?? 0;
    ?>
    
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Sistem Informasi Manajemen Rumah Sakit Terintegrasi</p>
            <div style="margin-top: 15px;">
                <span style="background: #3498db; color: white; padding: 5px 15px; border-radius: 20px; margin: 0 5px;">
                    📅 <?php echo date('l, d F Y'); ?>
                </span>
                <span style="background: #27ae60; color: white; padding: 5px 15px; border-radius: 20px; margin: 0 5px;">
                    🕒 <span id="current-time"></span>
                </span>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 2.5rem;">👥</div>
                <h3 style="margin: 10px 0 5px;">Total Pasien</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo $total_pasien; ?></div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #27ae60, #229954); color: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 2.5rem;">👨‍⚕️</div>
                <h3 style="margin: 10px 0 5px;">Total Dokter</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo $total_dokter; ?></div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 2.5rem;">�</div>
                <h3 style="margin: 10px 0 5px;">Total Obat</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo $total_obat; ?></div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22); color: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 2.5rem;">📋</div>
                <h3 style="margin: 10px 0 5px;">Rekam Medis</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo $total_rekam_medis; ?></div>
            </div>
        </div>

        <!-- Quick Alerts -->
        <?php if ($stok_rendah > 0 || $pasien_hari_ini > 0): ?>
        <div class="alerts" style="margin-bottom: 30px;">
            <?php if ($stok_rendah > 0): ?>
            <div class="alert alert-warning" style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404;">
                ⚠️ <strong>Peringatan:</strong> Ada <?php echo $stok_rendah; ?> obat dengan stok rendah (< 10). 
                <a href="obat_list.php" style="color: #856404; text-decoration: underline;">Lihat detail</a>
            </div>
            <?php endif; ?>
            
            <?php if ($pasien_hari_ini > 0): ?>
            <div class="alert alert-info">
                �📊 <strong>Info Hari Ini:</strong> <?php echo $pasien_hari_ini; ?> pasien telah dilayani hari ini.
                <a href="rekam_medis_list.php" style="color: #0c5460; text-decoration: underline;">Lihat rekam medis hari ini</a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Main Dashboard -->
        <div class="card">
            <h2>📊 Menu Utama</h2>
            <p>Selamat datang di sistem informasi rumah sakit. Pilih menu di bawah untuk mulai bekerja.</p>
            
            <div class="dashboard-grid">
                <div class="dashboard-item">
                    <div class="icon">👥</div>
                    <h3>Manajemen Pasien</h3>
                    <p style="margin-bottom: 15px; color: #7f8c8d;">Kelola data pasien rawat jalan dan rawat inap</p>
                    <a href="pasien_form.php" class="btn btn-primary">➕ Input Data Pasien</a>
                    <a href="pasien_list.php" class="btn btn-success">📝 Daftar Pasien</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">📋</div>
                    <h3>Rekam Medis</h3>
                    <p style="margin-bottom: 15px; color: #7f8c8d;">Input dan kelola rekam medis pasien</p>
                    <a href="rekam_medis_form.php" class="btn btn-primary">➕ Input Rekam Medis</a>
                    <a href="rekam_medis_list.php" class="btn btn-success">📋 Daftar Rekam Medis</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">👨‍⚕️</div>
                    <h3>Data Dokter</h3>
                    <p style="margin-bottom: 15px; color: #7f8c8d;">Kelola data dokter dan spesialis</p>
                    <a href="dokter_form.php" class="btn btn-primary">➕ Tambah Dokter</a>
                    <a href="dokter_list.php" class="btn btn-success">👨‍⚕️ Daftar Dokter</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">💊</div>
                    <h3>Manajemen Obat</h3>
                    <p style="margin-bottom: 15px; color: #7f8c8d;">Kelola inventori obat dan farmasi</p>
                    <a href="obat_form.php" class="btn btn-primary">➕ Tambah Obat</a>
                    <a href="obat_list.php" class="btn btn-success">💊 Daftar Obat</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">💊</div>
                    <h3>Resep Obat</h3>
                    <p style="margin-bottom: 15px; color: #7f8c8d;">Kelola resep dan pemberian obat</p>
                    <a href="resep_form.php" class="btn btn-primary">➕ Buat Resep</a>
                    <a href="resep_list.php" class="btn btn-success">📋 Daftar Resep</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">🏥</div>
                    <h3>Rawat Inap</h3>
                    <p style="margin-bottom: 15px; color: #7f8c8d;">Kelola data rawat inap pasien</p>
                    <a href="rawat_inap_form.php" class="btn btn-primary">➕ Input Rawat Inap</a>
                    <a href="rawat_inap_list.php" class="btn btn-success">🏥 Daftar Rawat Inap</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">🔧</div>
                    <h3>Tindakan Medis</h3>
                    <p style="margin-bottom: 15px; color: #7f8c8d;">Kelola tindakan dan alat medis</p>
                    <a href="tindakan_medis_form.php" class="btn btn-primary">➕ Input Tindakan</a>
                    <a href="tindakan_medis_list.php" class="btn btn-success">🔧 Daftar Tindakan</a>
                </div>
                
                <div class="dashboard-item">
                    <div class="icon">🧾</div>
                    <h3>Laporan & Cetak</h3>
                    <p style="margin-bottom: 15px; color: #7f8c8d;">Generate laporan dan struk pembayaran</p>
                    <a href="struk_cetak.php" class="btn btn-primary">🖨️ Cetak Struk</a>
                    <a href="laporan.php" class="btn btn-success">📊 Laporan</a>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card">
            <h2>🕒 Aktivitas Terbaru</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <h4 style="color: #3498db; margin-bottom: 10px;">📋 Rekam Medis Terbaru</h4>
                    <?php
                    $recent_rekam = mysqli_query($conn, "SELECT rm.*, p.Nm_Pasien, d.nm_dokter 
                                                         FROM rekam_medis rm 
                                                         LEFT JOIN pasien p ON rm.id_Pasien = p.Id_Pasien 
                                                         LEFT JOIN dokter d ON rm.id_dokter = d.id_dokter 
                                                         ORDER BY rm.tgl_periksa DESC LIMIT 5");
                    if ($recent_rekam && mysqli_num_rows($recent_rekam) > 0):
                        while ($rekam = mysqli_fetch_assoc($recent_rekam)):
                    ?>
                    <div style="background: #f8f9fa; padding: 10px; margin-bottom: 8px; border-radius: 8px; border-left: 4px solid #3498db;">
                        <strong><?php echo $rekam['Nm_Pasien']; ?></strong><br>
                        <small style="color: #7f8c8d;">Dr. <?php echo $rekam['nm_dokter']; ?> - <?php echo format_datetime($rekam['tgl_periksa']); ?></small>
                    </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <p style="color: #7f8c8d; font-style: italic;">Belum ada rekam medis</p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <h4 style="color: #27ae60; margin-bottom: 10px;">👥 Pasien Terbaru</h4>
                    <?php
                    $recent_pasien = mysqli_query($conn, "SELECT * FROM pasien ORDER BY created_at DESC LIMIT 5");
                    if ($recent_pasien && mysqli_num_rows($recent_pasien) > 0):
                        while ($pasien = mysqli_fetch_assoc($recent_pasien)):
                    ?>
                    <div style="background: #f8f9fa; padding: 10px; margin-bottom: 8px; border-radius: 8px; border-left: 4px solid #27ae60;">
                        <strong><?php echo $pasien['Nm_Pasien']; ?></strong><br>
                        <small style="color: #7f8c8d;"><?php echo $pasien['Jenis_Pasien']; ?> - <?php echo format_date($pasien['Tgl_Masuk']); ?></small>
                    </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <p style="color: #7f8c8d; font-style: italic;">Belum ada pasien terdaftar</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            document.getElementById('current-time').textContent = timeString;
        }
        
        // Update time every second
        setInterval(updateTime, 1000);
        updateTime(); // Initial call
        
        // Add some animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.dashboard-item');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>