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
            <h2>👥 Daftar Pasien</h2>
              <?php
            $result = mysqli_query($conn, "SELECT * FROM pasien ORDER BY nama");
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
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th>No. KTP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>                <tbody>
                    <?php
                    $no = 1;
                    if ($result) {
                        mysqli_data_seek($result, 0); // Reset pointer
                        while ($row = mysqli_fetch_assoc($result)) {
                            $umur = date('Y') - date('Y', strtotime($row['tgl_lahir']));
                            echo "<tr>
                                <td>".$no++."</td>
                                <td><strong>".$row['nama']."</strong></td>
                                <td>".($row['jenis_kelamin'] ?? 'Tidak diisi')."</td>
                                <td>".date('d/m/Y', strtotime($row['tgl_lahir']))." <small>($umur tahun)</small></td>
                                <td>".$row['alamat']."</td>
                                <td>".($row['no_telepon'] ?? 'Tidak diisi')."</td>
                                <td>".($row['no_ktp'] ?? 'Tidak diisi')."</td>
                                <td>
                                    <a href='rekam_medis_form.php?id_pasien=".$row['id']."' class='btn btn-primary btn-sm'>📋 Rekam Medis</a>
                                </td>
                            </tr>";
                        }
                    }
                    
                    if ($total_pasien == 0) {
                        echo "<tr><td colspan='8' style='text-align: center; color: #7f8c8d;'>Belum ada data pasien. <a href='pasien_form.php'>Tambah pasien pertama</a></td></tr>";
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