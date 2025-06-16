<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Rekam Medis - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Riwayat Rekam Medis Pasien</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="rekam_medis_form.php">➕ Input Rekam Medis</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
            <a href="dokter_list.php">👨‍⚕️ Daftar Dokter</a>
        </div>

        <div class="card">
            <h2>📋 Daftar Rekam Medis</h2>
            
            <?php
            $sql = "SELECT rm.*, p.Nm_Pasien as nama_pasien, p.JK, p.Umur, 
                           d.nm_dokter as nama_dokter, jk.Nmbidang_keahlian as spesialis
                    FROM rekam_medis rm 
                    LEFT JOIN pasien p ON rm.id_Pasien = p.Id_Pasien 
                    LEFT JOIN dokter d ON rm.id_dokter = d.id_dokter 
                    LEFT JOIN jenis_keahlian jk ON d.Kode_Keahlian = jk.Kode_keahlian
                    ORDER BY rm.tgl_periksa DESC";
            $result = mysqli_query($conn, $sql);
            $total_rekam = 0;
            if ($result) {
                $total_rekam = mysqli_num_rows($result);
            }
            ?>
            
            <div class="alert alert-info">
                📊 Total Rekam Medis: <strong><?php echo $total_rekam; ?></strong> catatan
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Keluhan</th>
                        <th>Diagnosa</th>
                        <th>Vital Sign</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $jk_icon = ($row['JK'] == 'L') ? '👨' : '👩';
                            $vital_sign = '';
                            if ($row['TB'] || $row['BB'] || $row['Tensi_darah']) {
                                $vital_sign = "TB: ".($row['TB'] ?? '-')." cm<br>";
                                $vital_sign .= "BB: ".($row['BB'] ?? '-')." kg<br>";
                                $vital_sign .= "TD: ".($row['Tensi_darah'] ?? '-');
                            }
                            
                            echo "<tr>
                                <td>".$no++."</td>
                                <td>".date('d/m/Y H:i', strtotime($row['tgl_periksa']))."</td>
                                <td>$jk_icon <strong>".$row['nama_pasien']."</strong><br><small>(".$row['Umur']." tahun)</small></td>
                                <td>👨‍⚕️ <strong>Dr. ".$row['nama_dokter']."</strong><br><small>".$row['spesialis']."</small></td>
                                <td>".substr($row['keluhan'], 0, 100).(strlen($row['keluhan']) > 100 ? '...' : '')."</td>
                                <td>".substr($row['diagnosa'], 0, 100).(strlen($row['diagnosa']) > 100 ? '...' : '')."</td>
                                <td><small>$vital_sign</small></td>
                                <td>
                                    <button onclick='viewDetail(".$row['id'].")' class='btn btn-primary btn-sm'>👁️ Detail</button>
                                    <a href='struk_cetak.php?id=".$row['id']."' class='btn btn-success btn-sm' target='_blank'>🖨️ Cetak</a>
                                </td>
                            </tr>";
                        }
                    }
                    
                    if ($total_rekam == 0) {
                        echo "<tr><td colspan='8' style='text-align: center; color: #7f8c8d;'>Belum ada data rekam medis. <a href='rekam_medis_form.php'>Input rekam medis pertama</a></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                <a href="rekam_medis_form.php" class="btn btn-primary">➕ Input Rekam Medis Baru</a>
                <a href="index.php" class="btn btn-success">🏠 Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
    
    <!-- Modal Detail -->
    <div id="detailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 15px; max-width: 700px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <h3>📋 Detail Rekam Medis</h3>
            <div id="detailContent"></div>
            <button onclick="closeModal()" class="btn btn-primary">Tutup</button>
        </div>
    </div>
    
    <script>
    function viewDetail(id) {
        // Fetch detail via AJAX atau ambil dari PHP
        fetch('get_rekam_detail.php?id=' + id)
            .then(response => response.text())
            .then(data => {
                document.getElementById('detailContent').innerHTML = data;
                document.getElementById('detailModal').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengambil detail rekam medis');
            });
    }
    
    function closeModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        var modal = document.getElementById('detailModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    </script>
</body>
</html>
