<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dokter - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Daftar Dokter dan Spesialis</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="pasien_form.php">👤 Tambah Pasien</a>
            <a href="rekam_medis_form.php">📋 Input Rekam Medis</a>
            <a href="obat_list.php">💊 Daftar Obat</a>
        </div>

        <div class="card">
            <h2>👨‍⚕️ Daftar Dokter</h2>
            
            <?php
            $result = mysqli_query($conn, "SELECT * FROM dokter ORDER BY nama");
            $total_dokter = 0;
            if ($result) {
                $total_dokter = mysqli_num_rows($result);
            }
            ?>
            
            <div class="alert alert-info">
                📊 Total Dokter: <strong><?php echo $total_dokter; ?></strong> orang
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dokter</th>
                        <th>Spesialis</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>".$no++."</td>
                                <td><strong>Dr. ".$row['nama']."</strong></td>
                                <td><span style='background: #3498db; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;'>".$row['spesialis']."</span></td>
                                <td><span style='background: #27ae60; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;'>✅ Aktif</span></td>
                                <td>
                                    <a href='rekam_medis_form.php?id_dokter=".$row['id']."' class='btn btn-primary btn-sm'>📋 Buat Rekam Medis</a>
                                </td>
                            </tr>";
                        }
                    }
                    
                    if ($total_dokter == 0) {
                        echo "<tr><td colspan='5' style='text-align: center; color: #7f8c8d;'>Belum ada data dokter.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                <a href="rekam_medis_form.php" class="btn btn-primary">📋 Input Rekam Medis</a>
                <a href="index.php" class="btn btn-success">🏠 Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>