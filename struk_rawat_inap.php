<?php 
include 'db.php'; 

// Get data rawat inap
$rawat_inap = null;
if (isset($_GET['id']) && isset($_GET['kelas']) && isset($_GET['tgl_inap'])) {
    $id_pasien = (int)$_GET['id'];
    $kelas = sanitize_input($_GET['kelas']);
    $tgl_inap = $_GET['tgl_inap'];
    
    $sql = "SELECT di.*, p.Nm_Pasien, p.Alamat, p.Tgl_lahir, p.Tlpn, p.JK, p.Umur,
                   dr.nm_Rinap, dr.Tarif
            FROM detail_inap di 
            LEFT JOIN pasien p ON di.Id_Pasien = p.Id_Pasien 
            LEFT JOIN data_ruang dr ON di.kelas = dr.Kelas
            WHERE di.Id_Pasien = $id_pasien AND di.kelas = '$kelas' AND di.tgl_inap = '$tgl_inap'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $rawat_inap = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Rawat Inap - Rumah Sakit</title>
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
            <p>Struk Pembayaran Rawat Inap</p>
        </div>
        
        <div class="nav no-print">
            <a href="index.php">🏠 Dashboard</a>
            <a href="rawat_inap_list.php">🏥 Daftar Rawat Inap</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
        </div>

        <div class="card">
            <?php if ($rawat_inap): ?>
                <div class="struk">
                    <div class="struk-header">
                        <h2>🏥 RUMAH SAKIT MODERN</h2>
                        <p>Jl. Kesehatan No. 123<br>
                        Telp: (021) 1234-5678<br>
                        ================================</p>
                        <h3>KWITANSI RAWAT INAP</h3>
                        <p>No: RI-<?php echo str_pad($rawat_inap['Id_Pasien'], 6, '0', STR_PAD_LEFT); ?></p>
                    </div>
                    
                    <div class="struk-content">
                        <div class="info-row">
                            <span><strong>Tanggal:</strong></span>
                            <span><?php echo date('d/m/Y H:i'); ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Pasien:</strong></span>
                            <span><?php echo $rawat_inap['Nm_Pasien']; ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>ID Pasien:</strong></span>
                            <span>P<?php echo str_pad($rawat_inap['Id_Pasien'], 4, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Umur:</strong></span>
                            <span><?php echo $rawat_inap['Umur']; ?> tahun</span>
                        </div>
                        <div class="info-row">
                            <span><strong>Jenis Kelamin:</strong></span>
                            <span><?php echo ($rawat_inap['JK'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Alamat:</strong></span>
                            <span style="font-size: 10px;"><?php echo substr($rawat_inap['Alamat'], 0, 30) . (strlen($rawat_inap['Alamat']) > 30 ? '...' : ''); ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Telepon:</strong></span>
                            <span><?php echo $rawat_inap['Tlpn'] ?? '-'; ?></span>
                        </div>
                        
                        <hr style="border: 1px dashed #333; margin: 15px 0;">
                        
                        <div class="info-row">
                            <span><strong>Kelas Ruang:</strong></span>
                            <span><?php echo $rawat_inap['nm_Rinap']; ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Tanggal Masuk:</strong></span>
                            <span><?php echo format_date($rawat_inap['tgl_inap']); ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Tanggal Keluar:</strong></span>
                            <span><?php echo $rawat_inap['Tgl_keluarinap'] ? format_date($rawat_inap['Tgl_keluarinap']) : '-'; ?></span>
                        </div>
                        <div class="info-row">
                            <span><strong>Lama Rawat Inap:</strong></span>
                            <span><?php echo $rawat_inap['lama_inap'] ? $rawat_inap['lama_inap'] . ' hari' : '-'; ?></span>
                        </div>
                        
                        <hr style="border: 1px dashed #333; margin: 15px 0;">
                        
                        <div class="struk-item">
                            <span>Tarif per hari</span>
                            <span><?php echo format_currency($rawat_inap['Tarif']); ?></span>
                        </div>
                        <?php if ($rawat_inap['lama_inap']): ?>
                        <div class="struk-item">
                            <span>Lama inap (<?php echo $rawat_inap['lama_inap']; ?> hari)</span>
                            <span><?php echo $rawat_inap['lama_inap']; ?> x <?php echo format_currency($rawat_inap['Tarif']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php
                        // Ambil data tindakan medis untuk pasien ini
                        $tindakan_sql = "SELECT dtm.*, tm.nm_alatmedik, tm.harga_alatMedik
                                        FROM detail_tindak_medis dtm 
                                        LEFT JOIN tindak_medis tm ON dtm.Kode_tindakMedis = tm.kode_tindakMedis
                                        WHERE dtm.Id_Pasien = ".$rawat_inap['Id_Pasien'];
                        $tindakan_result = mysqli_query($conn, $tindakan_sql);
                        $total_tindakan = 0;
                        
                        if ($tindakan_result && mysqli_num_rows($tindakan_result) > 0):
                        ?>
                        <hr style="border: 1px dashed #333; margin: 15px 0;">
                        <div style="font-weight: bold; margin-bottom: 10px;">TINDAKAN MEDIS:</div>
                        <?php
                        while ($tindakan = mysqli_fetch_assoc($tindakan_result)):
                            $subtotal_tindakan = $tindakan['Jum_alatmedik'] * $tindakan['harga_alatMedik'];
                            $total_tindakan += $subtotal_tindakan;
                        ?>
                        <div class="struk-item" style="font-size: 11px;">
                            <span><?php echo $tindakan['nm_alatmedik']; ?> (<?php echo $tindakan['Jum_alatmedik']; ?>x)</span>
                            <span><?php echo format_currency($subtotal_tindakan); ?></span>
                        </div>
                        <?php endwhile; ?>
                        <?php endif; ?>
                        
                        <div class="struk-total">
                            <?php 
                            $total_keseluruhan = ($rawat_inap['total_Inap'] ?? 0) + $total_tindakan;
                            ?>
                            <div class="struk-item">
                                <span>Subtotal Rawat Inap</span>
                                <span><?php echo format_currency($rawat_inap['total_Inap'] ?? 0); ?></span>
                            </div>
                            <?php if ($total_tindakan > 0): ?>
                            <div class="struk-item">
                                <span>Subtotal Tindakan Medis</span>
                                <span><?php echo format_currency($total_tindakan); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="struk-item" style="font-size: 16px;">
                                <span><strong>TOTAL BAYAR</strong></span>
                                <span><strong><?php echo format_currency($total_keseluruhan); ?></strong></span>
                            </div>
                        </div>
                        
                        <hr style="border: 1px dashed #333; margin: 15px 0;">
                        
                        <div style="text-align: center; font-size: 11px;">
                            <p>Terima kasih atas kepercayaan Anda<br>
                            pada Rumah Sakit Modern</p>
                            <p>Semoga lekas sembuh!</p>
                            <p>================================</p>
                            <p>Dicetak pada: <?php echo date('d/m/Y H:i:s'); ?></p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="struk">
                    <div class="struk-header">
                        <h2>🏥 RUMAH SAKIT MODERN</h2>
                        <p>Jl. Kesehatan No. 123<br>
                        Telp: (021) 1234-5678<br>
                        ================================</p>
                        <h3>DATA TIDAK DITEMUKAN</h3>
                    </div>
                    
                    <div style="text-align: center; padding: 20px;">
                        <p>❌ Data rawat inap tidak ditemukan</p>
                        <p>Silakan periksa kembali parameter yang diberikan.</p>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="no-print" style="text-align: center; margin-top: 20px;">
                <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Struk</button>
                <a href="rawat_inap_list.php" class="btn btn-success">🏥 Daftar Rawat Inap</a>
                <a href="index.php" class="btn btn-success">🏠 Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
