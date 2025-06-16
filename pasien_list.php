<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Daftar Pasien Terdaftar</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="pasien_form.php">👤 Tambah Pasien</a>
            <a href="rekam_medis_form.php">📋 Input Rekam Medis</a>
            <a href="dokter_list.php">👨‍⚕️ Daftar Dokter</a>
        </div>

        <div class="card">
            <h2>👥 Daftar Pasien</h2>            <?php
            $result = mysqli_query($conn, "SELECT * FROM pasien ORDER BY Nm_Pasien");
            $total_pasien = 0;
            if ($result) {
                $total_pasien = mysqli_num_rows($result);
            }
            ?>
            
            <div class="alert alert-info">
                📊 Total Pasien Terdaftar: <strong><?php echo $total_pasien; ?></strong> orang
            </div>
              <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pasien</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Pasien</th>
                        <th>JK</th>
                        <th>Umur</th>
                        <th>Tempat, Tgl Lahir</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Tgl Masuk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($result) {
                        mysqli_data_seek($result, 0); // Reset pointer
                        while ($row = mysqli_fetch_assoc($result)) {
                            $jk_full = ($row['JK'] == 'L') ? 'Laki-laki' : 'Perempuan';
                            $jenis_badge = ($row['Jenis_Pasien'] == 'Rawat Inap') ? 
                                '<span style="background: #e74c3c; color: white; padding: 3px 8px; border-radius: 10px; font-size: 0.8rem;">🏥 Rawat Inap</span>' : 
                                '<span style="background: #27ae60; color: white; padding: 3px 8px; border-radius: 10px; font-size: 0.8rem;">🚶 Rawat Jalan</span>';
                            
                            echo "<tr>
                                <td>".$no++."</td>
                                <td><strong>P".str_pad($row['Id_Pasien'], 4, '0', STR_PAD_LEFT)."</strong></td>
                                <td><strong>".$row['Nm_Pasien']."</strong></td>
                                <td>$jenis_badge</td>
                                <td>".$jk_full."</td>
                                <td>".$row['Umur']." tahun</td>
                                <td>".$row['Tmpt_Lahir'].", ".date('d/m/Y', strtotime($row['Tgl_lahir']))."</td>
                                <td>".$row['Alamat']."</td>
                                <td>".($row['Tlpn'] ?? '-')."</td>
                                <td>".date('d/m/Y', strtotime($row['Tgl_Masuk']))."</td>
                                <td>
                                    <a href='rekam_medis_form.php?id_pasien=".$row['Id_Pasien']."' class='btn btn-primary btn-sm'>📋 Rekam Medis</a>
                                </td>
                            </tr>";
                        }
                    }
                    
                    if ($total_pasien == 0) {
                        echo "<tr><td colspan='11' style='text-align: center; color: #7f8c8d;'>Belum ada data pasien. <a href='pasien_form.php'>Tambah pasien pertama</a></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                <a href="pasien_form.php" class="btn btn-primary">➕ Tambah Pasien Baru</a>
                <a href="index.php" class="btn btn-success">🏠 Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>