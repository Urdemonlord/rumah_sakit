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
            $sql = "SELECT rm.*, p.nama as nama_pasien, d.nama as nama_dokter, d.spesialis 
                    FROM rekam_medis rm 
                    LEFT JOIN pasien p ON rm.id_pasien = p.id 
                    LEFT JOIN dokter d ON rm.id_dokter = d.id 
                    ORDER BY rm.tanggal_periksa DESC";
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
                        <th>Tindakan</th>
                        <th>Obat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $tanggal = $row['tanggal_periksa'] ? date('d/m/Y H:i', strtotime($row['tanggal_periksa'])) : 'Tidak ada tanggal';
                            echo "<tr>
                                <td>".$no++."</td>
                                <td>$tanggal</td>
                                <td><strong>".($row['nama_pasien'] ?? 'ID: '.$row['id_pasien'])."</strong></td>
                                <td>Dr. ".($row['nama_dokter'] ?? 'ID: '.$row['id_dokter'])."<br><small>".($row['spesialis'] ?? '')."</small></td>
                                <td>".substr($row['keluhan'], 0, 50).(strlen($row['keluhan']) > 50 ? '...' : '')."</td>
                                <td>".substr($row['diagnosa'], 0, 50).(strlen($row['diagnosa']) > 50 ? '...' : '')."</td>
                                <td>".substr($row['tindakan'], 0, 50).(strlen($row['tindakan']) > 50 ? '...' : '')."</td>
                                <td>".($row['obat'] ? substr($row['obat'], 0, 30).(strlen($row['obat']) > 30 ? '...' : '') : 'Tidak ada')."</td>
                                <td>
                                    <button class='btn btn-primary btn-sm' onclick='viewDetail(".$row['id'].")'>👁️ Detail</button>
                                    <a href='struk_cetak.php?id=".$row['id']."' class='btn btn-success btn-sm'>🖨️ Cetak</a>
                                </td>
                            </tr>";
                        }
                    }
                    
                    if ($total_rekam == 0) {
                        echo "<tr><td colspan='9' style='text-align: center; color: #7f8c8d;'>Belum ada rekam medis. <a href='rekam_medis_form.php'>Buat rekam medis pertama</a></td></tr>";
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
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 15px; max-width: 600px; width: 90%;">
            <h3>📋 Detail Rekam Medis</h3>
            <div id="detailContent"></div>
            <button onclick="closeModal()" class="btn btn-primary">Tutup</button>
        </div>
    </div>
    
    <script>
    function viewDetail(id) {
        // Simple implementation - in real app, you'd fetch via AJAX
        var modal = document.getElementById('detailModal');
        var content = document.getElementById('detailContent');
        
        // Get row data
        var rows = document.querySelectorAll('tbody tr');
        var targetRow = null;
        
        for (var i = 0; i < rows.length; i++) {
            if (rows[i].querySelector('button[onclick="viewDetail(' + id + ')"]')) {
                targetRow = rows[i];
                break;
            }
        }
        
        if (targetRow) {
            var cells = targetRow.cells;
            content.innerHTML = 
                '<p><strong>Tanggal:</strong> ' + cells[1].textContent + '</p>' +
                '<p><strong>Pasien:</strong> ' + cells[2].textContent + '</p>' +
                '<p><strong>Dokter:</strong> ' + cells[3].innerHTML + '</p>' +
                '<p><strong>Keluhan:</strong> ' + cells[4].textContent + '</p>' +
                '<p><strong>Diagnosa:</strong> ' + cells[5].textContent + '</p>' +
                '<p><strong>Tindakan:</strong> ' + cells[6].textContent + '</p>' +
                '<p><strong>Obat:</strong> ' + cells[7].textContent + '</p>';
        }
        
        modal.style.display = 'block';
    }
    
    function closeModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
    </script>
</body>
</html>