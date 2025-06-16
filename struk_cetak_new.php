<?php 
include 'db.php'; 

// Get rekam medis data if ID provided
$rekam_medis = null;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT rm.*, p.Nm_Pasien as nama_pasien, p.Alamat, p.Tgl_lahir, p.Tlpn, p.JK, p.Umur,
                   d.nm_dokter as nama_dokter, jk.Nmbidang_keahlian as spesialis 
            FROM rekam_medis rm 
            LEFT JOIN pasien p ON rm.id_Pasien = p.Id_Pasien 
            LEFT JOIN dokter d ON rm.id_dokter = d.id_dokter 
            LEFT JOIN jenis_keahlian jk ON d.Kode_Keahlian = jk.Kode_keahlian
            WHERE rm.id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $rekam_medis = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .card { box-shadow: none; border: 1px solid #000; }
        }
        .struk {
            max-width: 500px;
            margin: 0 auto;
            font-family: 'Courier New', monospace;
        }
        .struk-header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .struk-item {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .struk-total {
            border-top: 2px dashed #333;
            padding-top: 10px;
            margin-top: 15px;
            font-weight: bold;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header no-print">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Struk Pembayaran & Kwitansi</p>
        </div>
        
        <div class="nav no-print">
            <a href="index.php">🏠 Dashboard</a>
            <a href="rekam_medis_list.php">📋 Daftar Rekam Medis</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
        </div>

        <div class="card">
            <?php if ($rekam_medis): ?>
                <div class="struk">
                    <div class="struk-header">
                        <h2>🏥 RUMAH SAKIT MODERN</h2>
                        <p>Jl. Kesehatan No. 123<br>
                        Telp: (021) 1234-5678<br>
                        ================================</p>
                        <h3>KWITANSI PEMBAYARAN</h3>
                        <p>No: RM-<?php echo str_pad($rekam_medis['id'], 6, '0', STR_PAD_LEFT); ?></p>
                    </div>
                    
                    <div class="struk-content">
                        <div class="info-row">
                            <span><strong>Tanggal:</strong></span>
                            <span><?php echo date('d/m/Y H:i', strtotime($rekam_medis['tgl_periksa'])); ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Pasien:</strong></span>
                            <span><?php echo $rekam_medis['nama_pasien']; ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Umur:</strong></span>
                            <span><?php echo $rekam_medis['Umur']; ?> tahun</span>
                        </div>
                        <div class="info-row">
                            <span><strong>JK:</strong></span>
                            <span><?php echo ($rekam_medis['JK'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Alamat:</strong></span>
                            <span><?php echo substr($rekam_medis['Alamat'], 0, 30); ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Telepon:</strong></span>
                            <span><?php echo $rekam_medis['Tlpn'] ?? '-'; ?></span>
                        </div>
                        
                        <br>
                        <div class="info-row">
                            <span><strong>Dokter:</strong></span>
                            <span>Dr. <?php echo $rekam_medis['nama_dokter']; ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Spesialis:</strong></span>
                            <span><?php echo $rekam_medis['spesialis']; ?></span>
                        </div>
                        
                        <hr style="border: 1px dashed #333;">
                        
                        <div class="struk-item">
                            <span>Konsultasi Dokter</span>
                            <span>Rp 150,000</span>
                        </div>
                        
                        <?php if ($rekam_medis['TB'] || $rekam_medis['BB'] || $rekam_medis['Tensi_darah']): ?>
                        <div class="struk-item">
                            <span>Pemeriksaan Fisik</span>
                            <span>Rp 25,000</span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="struk-item">
                            <span>Administrasi</span>
                            <span>Rp 10,000</span>
                        </div>
                        
                        <?php 
                        $total = 150000 + 10000;
                        if ($rekam_medis['TB'] || $rekam_medis['BB'] || $rekam_medis['Tensi_darah']) {
                            $total += 25000;
                        }
                        ?>
                        
                        <div class="struk-total">
                            <div class="struk-item">
                                <span><strong>TOTAL BAYAR</strong></span>
                                <span><strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong></span>
                            </div>
                        </div>
                        
                        <br>
                        <p style="text-align: center; font-size: 10px;">
                            ================================<br>
                            Diagnosa: <?php echo substr($rekam_medis['diagnosa'], 0, 40); ?><br>
                            <?php if ($rekam_medis['TB']): ?>TB: <?php echo $rekam_medis['TB']; ?>cm <?php endif; ?>
                            <?php if ($rekam_medis['BB']): ?>BB: <?php echo $rekam_medis['BB']; ?>kg <?php endif; ?>
                            <?php if ($rekam_medis['Tensi_darah']): ?>TD: <?php echo $rekam_medis['Tensi_darah']; ?><?php endif; ?>
                            <br>================================<br>
                            Terima kasih atas kepercayaan Anda<br>
                            Semoga lekas sembuh<br>
                            ================================
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <div class="struk">
                    <div class="struk-header">
                        <h2>🏥 RUMAH SAKIT MODERN</h2>
                        <p>Jl. Kesehatan No. 123<br>
                        Telp: (021) 1234-5678<br>
                        ================================</p>
                        <h3>KWITANSI UMUM</h3>
                    </div>
                    
                    <div class="struk-content">
                        <p>Tanggal: <?php echo date('d/m/Y H:i'); ?></p>
                        <p>Kasir: Admin</p>
                        
                        <hr style="border: 1px dashed #333;">
                        
                        <div class="struk-item">
                            <span>Layanan Umum</span>
                            <span>Rp 50,000</span>
                        </div>
                        
                        <div class="struk-total">
                            <div class="struk-item">
                                <span><strong>TOTAL BAYAR</strong></span>
                                <span><strong>Rp 50,000</strong></span>
                            </div>
                        </div>
                        
                        <p style="text-align: center; font-size: 10px; margin-top: 20px;">
                            ================================<br>
                            Terima kasih atas kunjungan Anda<br>
                            ================================
                        </p>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="no-print" style="text-align: center; margin-top: 30px;">
                <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Struk</button>
                <a href="rekam_medis_list.php" class="btn btn-success">📋 Kembali ke Rekam Medis</a>
                <a href="index.php" class="btn btn-success">🏠 Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
