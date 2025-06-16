<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tindakan Medis - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏥</text></svg>">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Daftar Tindakan Medis dan Penggunaan Alat</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="tindakan_medis_form.php">➕ Input Tindakan Medis</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
            <a href="rekam_medis_list.php">📋 Rekam Medis</a>
        </div>

        <div class="card">
            <!-- Tab Navigation -->
            <div style="margin-bottom: 20px;">
                <button class="btn btn-primary" onclick="showTab('master')" id="btn-master">📝 Master Tindakan Medis</button>
                <button class="btn btn-success" onclick="showTab('detail')" id="btn-detail">👤 Riwayat Tindakan Pasien</button>
            </div>
            
            <!-- Master Tindakan Medis -->
            <div id="master-tab">
                <h2>🔧 Master Tindakan Medis</h2>
                
                <?php
                $result_master = mysqli_query($conn, "SELECT * FROM tindak_medis ORDER BY nm_alatmedik");
                $total_tindakan = 0;
                if ($result_master) {
                    $total_tindakan = mysqli_num_rows($result_master);
                }
                ?>
                
                <div class="alert alert-info">
                    📊 Total Jenis Tindakan: <strong><?php echo $total_tindakan; ?></strong> jenis
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Alat/Tindakan</th>
                            <th>Jenis Medis</th>
                            <th>Jumlah Tersedia</th>
                            <th>Tarif</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if ($result_master && mysqli_num_rows($result_master) > 0) {
                            while ($row = mysqli_fetch_assoc($result_master)) {
                                $status_alat = $row['jum_alatmedik'] > 0 ? 
                                    '<span style="background: #27ae60; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">✅ Tersedia</span>' :
                                    '<span style="background: #e74c3c; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">❌ Tidak Tersedia</span>';
                                
                                $jenis_badge = '';
                                switch($row['Jenis_medik']) {
                                    case 'Pemeriksaan':
                                        $jenis_badge = '<span style="background: #3498db; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">🔍 Pemeriksaan</span>';
                                        break;
                                    case 'Diagnostik':
                                        $jenis_badge = '<span style="background: #9b59b6; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">🔬 Diagnostik</span>';
                                        break;
                                    case 'Terapi':
                                        $jenis_badge = '<span style="background: #f39c12; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">💊 Terapi</span>';
                                        break;
                                    case 'Bedah':
                                        $jenis_badge = '<span style="background: #e74c3c; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">🔪 Bedah</span>';
                                        break;
                                    default:
                                        $jenis_badge = '<span style="background: #95a5a6; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">'.$row['Jenis_medik'].'</span>';
                                }
                                
                                echo "<tr>
                                    <td>".$no++."</td>
                                    <td><strong>".$row['kode_tindakMedis']."</strong></td>
                                    <td><strong>".$row['nm_alatmedik']."</strong></td>
                                    <td>".$jenis_badge."</td>
                                    <td>".$row['jum_alatmedik']." unit</td>
                                    <td>".format_currency($row['harga_alatMedik'])."</td>
                                    <td>".$status_alat."</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center; color: #7f8c8d;'>Belum ada data tindakan medis. <a href='tindakan_medis_form.php'>Tambah tindakan medis pertama</a></td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Detail Tindakan Pasien -->
            <div id="detail-tab" style="display: none;">
                <h2>👤 Riwayat Tindakan Medis Pasien</h2>
                
                <?php
                $sql_detail = "SELECT dtm.*, p.Nm_Pasien, p.JK, p.Umur, tm.nm_alatmedik, tm.Jenis_medik, tm.harga_alatMedik
                               FROM detail_tindak_medis dtm 
                               LEFT JOIN pasien p ON dtm.Id_Pasien = p.Id_Pasien 
                               LEFT JOIN tindak_medis tm ON dtm.Kode_tindakMedis = tm.kode_tindakMedis
                               ORDER BY dtm.Id_Pasien DESC";
                $result_detail = mysqli_query($conn, $sql_detail);
                $total_detail = 0;
                $total_biaya_keseluruhan = 0;
                
                if ($result_detail) {
                    $total_detail = mysqli_num_rows($result_detail);
                    // Hitung total biaya keseluruhan
                    mysqli_data_seek($result_detail, 0);
                    while ($row = mysqli_fetch_assoc($result_detail)) {
                        $total_biaya_keseluruhan += ($row['Jum_alatmedik'] * $row['harga_alatMedik']);
                    }
                    mysqli_data_seek($result_detail, 0); // Reset pointer
                }
                ?>
                
                <div class="alert alert-info">
                    📊 Total Tindakan: <strong><?php echo $total_detail; ?></strong> record | 
                    💰 Total Nilai: <strong><?php echo format_currency($total_biaya_keseluruhan); ?></strong>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pasien</th>
                            <th>Nama Pasien</th>
                            <th>JK</th>
                            <th>Umur</th>
                            <th>Kode Tindakan</th>
                            <th>Nama Tindakan</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Tarif</th>
                            <th>Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if ($result_detail && mysqli_num_rows($result_detail) > 0) {
                            while ($row = mysqli_fetch_assoc($result_detail)) {
                                $jk_full = ($row['JK'] == 'L') ? 'Laki-laki' : 'Perempuan';
                                $total_biaya_row = $row['Jum_alatmedik'] * $row['harga_alatMedik'];
                                
                                $jenis_badge = '';
                                switch($row['Jenis_medik']) {
                                    case 'Pemeriksaan':
                                        $jenis_badge = '<span style="background: #3498db; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">🔍</span>';
                                        break;
                                    case 'Diagnostik':
                                        $jenis_badge = '<span style="background: #9b59b6; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">🔬</span>';
                                        break;
                                    case 'Terapi':
                                        $jenis_badge = '<span style="background: #f39c12; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">💊</span>';
                                        break;
                                    case 'Bedah':
                                        $jenis_badge = '<span style="background: #e74c3c; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">🔪</span>';
                                        break;
                                    default:
                                        $jenis_badge = '<span style="background: #95a5a6; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">🔧</span>';
                                }
                                
                                echo "<tr>
                                    <td>".$no++."</td>
                                    <td><strong>P".str_pad($row['Id_Pasien'], 4, '0', STR_PAD_LEFT)."</strong></td>
                                    <td><strong>".$row['Nm_Pasien']."</strong></td>
                                    <td>".$jk_full."</td>
                                    <td>".$row['Umur']." tahun</td>
                                    <td><strong>".$row['Kode_tindakMedis']."</strong></td>
                                    <td>".$row['nm_alatmedik']."</td>
                                    <td>".$jenis_badge."</td>
                                    <td>".$row['Jum_alatmedik']." unit</td>
                                    <td>".format_currency($row['harga_alatMedik'])."</td>
                                    <td><strong>".format_currency($total_biaya_row)."</strong></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11' style='text-align: center; color: #7f8c8d;'>Belum ada riwayat tindakan medis. <a href='tindakan_medis_form.php'>Input tindakan pertama</a></td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 20px;">
                <a href="tindakan_medis_form.php" class="btn btn-primary">➕ Input Tindakan Medis</a>
                <a href="index.php" class="btn btn-success">🏠 Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.getElementById('master-tab').style.display = tabName === 'master' ? 'block' : 'none';
            document.getElementById('detail-tab').style.display = tabName === 'detail' ? 'block' : 'none';
            
            // Update button styles
            document.getElementById('btn-master').className = tabName === 'master' ? 'btn btn-primary' : 'btn btn-outline-primary';
            document.getElementById('btn-detail').className = tabName === 'detail' ? 'btn btn-success' : 'btn btn-outline-success';
        }
        
        // Add outline button styles
        const style = document.createElement('style');
        style.textContent = `
            .btn-outline-primary {
                background: transparent;
                color: #3498db;
                border: 2px solid #3498db;
            }
            .btn-outline-primary:hover {
                background: #3498db;
                color: white;
            }
            .btn-outline-success {
                background: transparent;
                color: #27ae60;
                border: 2px solid #27ae60;
            }
            .btn-outline-success:hover {
                background: #27ae60;
                color: white;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
