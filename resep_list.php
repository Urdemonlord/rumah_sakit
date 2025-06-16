<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Resep - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Daftar Resep Obat</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="resep_form.php">➕ Buat Resep</a>
            <a href="obat_list.php">💊 Daftar Obat</a>
            <a href="rekam_medis_list.php">📋 Rekam Medis</a>
        </div>

        <div class="card">
            <h2>📋 Daftar Resep Obat</h2>
            
            <?php
            $sql = "SELECT ro.*, p.Nm_Pasien as nama_pasien, p.JK, p.Umur
                    FROM resep_obat ro 
                    LEFT JOIN pasien p ON ro.Id_Pasien = p.Id_Pasien 
                    ORDER BY ro.tgl_resep DESC";
            $result = mysqli_query($conn, $sql);
            $total_resep = 0;
            if ($result) {
                $total_resep = mysqli_num_rows($result);
            }
            ?>
            
            <div class="alert alert-info">
                📊 Total Resep: <strong><?php echo $total_resep; ?></strong> resep
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Resep</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Total Obat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Hitung total jenis obat dalam resep
                            $sql_count = "SELECT COUNT(*) as total FROM detail_obat WHERE Kode_Resep = '".$row['Kode_Resep']."'";
                            $result_count = mysqli_query($conn, $sql_count);
                            $count_data = mysqli_fetch_assoc($result_count);
                            $total_obat = $count_data['total'];
                            
                            // Cek status pembayaran
                            $sql_struk = "SELECT * FROM struk_obat WHERE Kode_resep = '".$row['Kode_Resep']."'";
                            $result_struk = mysqli_query($conn, $sql_struk);
                            $status = mysqli_num_rows($result_struk) > 0 ? 'Sudah Bayar' : 'Belum Bayar';
                            $status_color = ($status == 'Sudah Bayar') ? '#27ae60' : '#f39c12';
                            
                            $jk_icon = ($row['JK'] == 'L') ? '👨' : '👩';
                            
                            echo "<tr>
                                <td>".$no++."</td>
                                <td><strong>".$row['Kode_Resep']."</strong></td>
                                <td>".date('d/m/Y H:i', strtotime($row['tgl_resep']))."</td>
                                <td>$jk_icon <strong>".$row['nama_pasien']."</strong><br><small>(".$row['Umur']." tahun)</small></td>
                                <td><span style='background: #3498db; color: white; padding: 3px 8px; border-radius: 10px; font-size: 0.8rem;'>$total_obat jenis</span></td>
                                <td><span style='background: $status_color; color: white; padding: 3px 8px; border-radius: 10px; font-size: 0.8rem;'>$status</span></td>
                                <td>
                                    <button onclick='viewDetail(\"".$row['Kode_Resep']."\")' class='btn btn-primary btn-sm'>👁️ Detail</button>";
                            
                            if ($status == 'Belum Bayar') {
                                echo " <a href='pembayaran_obat.php?kode=".$row['Kode_Resep']."' class='btn btn-success btn-sm'>💰 Bayar</a>";
                            } else {
                                echo " <a href='struk_obat.php?kode=".$row['Kode_Resep']."' class='btn btn-success btn-sm' target='_blank'>🖨️ Struk</a>";
                            }
                            
                            echo "</td>
                            </tr>";
                        }
                    }
                    
                    if ($total_resep == 0) {
                        echo "<tr><td colspan='7' style='text-align: center; color: #7f8c8d;'>Belum ada data resep. <a href='resep_form.php'>Buat resep pertama</a></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                <a href="resep_form.php" class="btn btn-primary">➕ Buat Resep Baru</a>
                <a href="index.php" class="btn btn-success">🏠 Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
    
    <!-- Modal Detail -->
    <div id="detailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 15px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <h3>💊 Detail Resep Obat</h3>
            <div id="detailContent"></div>
            <button onclick="closeModal()" class="btn btn-primary">Tutup</button>
        </div>
    </div>
    
    <script>
    function viewDetail(kode_resep) {
        fetch('get_resep_detail.php?kode=' + kode_resep)
            .then(response => response.text())
            .then(data => {
                document.getElementById('detailContent').innerHTML = data;
                document.getElementById('detailModal').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengambil detail resep');
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
