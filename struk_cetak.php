<?php 
include 'db.php'; 

// Get rekam medis data if ID provided
$rekam_medis = null;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT rm.*, p.nama as nama_pasien, p.alamat, p.tgl_lahir, p.no_telepon, 
                   d.nama as nama_dokter, d.spesialis 
            FROM rekam_medis rm 
            LEFT JOIN pasien p ON rm.id_pasien = p.id 
            LEFT JOIN dokter d ON rm.id_dokter = d.id 
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
            max-width: 400px;
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
                        <p>No: <?php echo str_pad($rekam_medis['id'], 6, '0', STR_PAD_LEFT); ?></p>
                    </div>
                    
                    <div class="struk-content">
                        <p><strong>Tanggal:</strong> <?php echo date('d/m/Y H:i', strtotime($rekam_medis['tanggal_periksa'])); ?></p>
                        <p><strong>Pasien:</strong> <?php echo $rekam_medis['nama_pasien']; ?></p>
                        <p><strong>Alamat:</strong> <?php echo $rekam_medis['alamat']; ?></p>
                        <p><strong>Telepon:</strong> <?php echo $rekam_medis['no_telepon'] ?? '-'; ?></p>
                        
                        <br>
                        <p><strong>Dokter:</strong> Dr. <?php echo $rekam_medis['nama_dokter']; ?></p>
                        <p><strong>Spesialis:</strong> <?php echo $rekam_medis['spesialis']; ?></p>
                        
                        <hr style="border: 1px dashed #333;">
                        
                        <div class="struk-item">
                            <span>Konsultasi Dokter</span>
                            <span>Rp 150.000</span>
                        </div>
                        
                        <?php if ($rekam_medis['obat']): ?>
                        <div class="struk-item">
                            <span>Obat-obatan</span>
                            <span>Rp 85.000</span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="struk-item">
                            <span>Administrasi</span>
                            <span>Rp 25.000</span>
                        </div>
                        
                        <div class="struk-total">
                            <div class="struk-item">
                                <span>TOTAL PEMBAYARAN</span>
                                <span>Rp <?php echo number_format($rekam_medis['obat'] ? 260000 : 175000, 0, ',', '.'); ?></span>
                            </div>
                        </div>
                        
                        <hr style="border: 1px dashed #333;">
                        
                        <p style="text-align: center; margin-top: 20px;">
                            <small>Terima kasih atas kepercayaan Anda<br>
                            Semoga lekas sembuh<br><br>
                            Struk ini adalah bukti pembayaran yang sah</small>
                        </p>
                        
                        <p style="text-align: center; margin-top: 30px;">
                            <strong>( Dr. <?php echo $rekam_medis['nama_dokter']; ?> )</strong><br>
                            <?php echo $rekam_medis['spesialis']; ?>
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
                        <h3>KWITANSI PEMBAYARAN</h3>
                        <p>No: 000001</p>
                    </div>
                    
                    <div class="struk-content">
                        <p><strong>Tanggal:</strong> <?php echo date('d/m/Y H:i'); ?></p>
                        <p><strong>Pasien:</strong> John Doe</p>
                        <p><strong>Alamat:</strong> Jl. Contoh No. 456</p>
                        <p><strong>Telepon:</strong> 08123456789</p>
                        
                        <br>
                        <p><strong>Dokter:</strong> Dr. Jane Smith</p>
                        <p><strong>Spesialis:</strong> Dokter Umum</p>
                        
                        <hr style="border: 1px dashed #333;">
                        
                        <div class="struk-item">
                            <span>Konsultasi Dokter</span>
                            <span>Rp 150.000</span>
                        </div>
                        
                        <div class="struk-item">
                            <span>Obat-obatan</span>
                            <span>Rp 85.000</span>
                        </div>
                        
                        <div class="struk-item">
                            <span>Administrasi</span>
                            <span>Rp 25.000</span>
                        </div>
                        
                        <div class="struk-total">
                            <div class="struk-item">
                                <span>TOTAL PEMBAYARAN</span>
                                <span>Rp 260.000</span>
                            </div>
                        </div>
                        
                        <hr style="border: 1px dashed #333;">
                        
                        <p style="text-align: center; margin-top: 20px;">
                            <small>Terima kasih atas kepercayaan Anda<br>
                            Semoga lekas sembuh<br><br>
                            Struk ini adalah bukti pembayaran yang sah</small>
                        </p>
                        
                        <p style="text-align: center; margin-top: 30px;">
                            <strong>( Dr. Jane Smith )</strong><br>
                            Dokter Umum
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