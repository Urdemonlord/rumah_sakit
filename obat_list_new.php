<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Obat - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Inventori Obat dan Farmasi</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="obat_form.php">➕ Tambah Obat</a>
            <a href="pasien_form.php">👤 Tambah Pasien</a>
            <a href="rekam_medis_form.php">📋 Input Rekam Medis</a>
        </div>

        <div class="card">
            <h2>💊 Daftar Obat</h2>
            
            <?php
            $result = mysqli_query($conn, "SELECT * FROM obat ORDER BY nm_obat");
            $total_obat = 0;
            $stok_rendah = 0;
            $kadaluarsa = 0;
            if ($result) {
                $total_obat = mysqli_num_rows($result);
                // Hitung obat dengan stok rendah dan kadaluarsa
                mysqli_data_seek($result, 0);
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['stok'] < 10) {
                        $stok_rendah++;
                    }
                    if ($row['tgl_kadaluarsa'] <= date('Y-m-d')) {
                        $kadaluarsa++;
                    }
                }
            }
            ?>
            
            <div class="alert alert-info">
                📊 Total Obat: <strong><?php echo $total_obat; ?></strong> jenis | 
                ⚠️ Stok Rendah: <strong><?php echo $stok_rendah; ?></strong> obat |
                🚫 Kadaluarsa: <strong><?php echo $kadaluarsa; ?></strong> obat
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Obat</th>
                        <th>Nama Obat</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Status Stok</th>
                        <th>Tgl Kadaluarsa</th>
                        <th>Letak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($result) {
                        mysqli_data_seek($result, 0);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $status_stok = '';
                            $status_color = '';
                            $status_kadaluarsa = '';
                            $kadaluarsa_color = '';
                            
                            // Status stok
                            if ($row['stok'] <= 0) {
                                $status_stok = 'Habis';
                                $status_color = '#e74c3c';
                            } elseif ($row['stok'] < 10) {
                                $status_stok = 'Stok Rendah';
                                $status_color = '#f39c12';
                            } else {
                                $status_stok = 'Tersedia';
                                $status_color = '#27ae60';
                            }
                            
                            // Status kadaluarsa
                            $today = date('Y-m-d');
                            $expire_date = $row['tgl_kadaluarsa'];
                            $days_diff = (strtotime($expire_date) - strtotime($today)) / (60 * 60 * 24);
                            
                            if ($days_diff <= 0) {
                                $status_kadaluarsa = 'Kadaluarsa';
                                $kadaluarsa_color = '#e74c3c';
                            } elseif ($days_diff <= 30) {
                                $status_kadaluarsa = 'Akan Kadaluarsa';
                                $kadaluarsa_color = '#f39c12';
                            } else {
                                $status_kadaluarsa = 'Aman';
                                $kadaluarsa_color = '#27ae60';
                            }
                            
                            echo "<tr>
                                <td>".$no++."</td>
                                <td><strong>".$row['Kode_obat']."</strong></td>
                                <td><strong>".$row['nm_obat']."</strong></td>
                                <td>".$row['stok']."</td>
                                <td>".$row['satuan']."</td>
                                <td>Rp ".number_format($row['harga_obat'], 0, ',', '.')."</td>
                                <td><span style='background: $status_color; color: white; padding: 3px 8px; border-radius: 10px; font-size: 0.8rem;'>$status_stok</span></td>
                                <td>".date('d/m/Y', strtotime($row['tgl_kadaluarsa']))."<br><span style='background: $kadaluarsa_color; color: white; padding: 2px 6px; border-radius: 8px; font-size: 0.7rem;'>$status_kadaluarsa</span></td>
                                <td>".$row['letak_obat']."</td>
                                <td>
                                    <button onclick='updateStok(\"".$row['Kode_obat']."\")' class='btn btn-primary btn-sm'>📦 Update Stok</button>
                                </td>
                            </tr>";
                        }
                    }
                    
                    if ($total_obat == 0) {
                        echo "<tr><td colspan='10' style='text-align: center; color: #7f8c8d;'>Belum ada data obat. <a href='obat_form.php'>Tambah obat pertama</a></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                <a href="obat_form.php" class="btn btn-primary">➕ Tambah Obat Baru</a>
                <a href="index.php" class="btn btn-success">🏠 Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
    
    <script>
    function updateStok(kode) {
        var stok = prompt("Masukkan stok baru untuk obat " + kode + ":");
        if (stok !== null && stok !== "" && !isNaN(stok)) {
            window.location.href = "obat_list.php?update_stok=" + kode + "&stok=" + stok;
        }
    }
    
    <?php
    // Handle update stok
    if (isset($_GET['update_stok']) && isset($_GET['stok'])) {
        $kode = mysqli_real_escape_string($conn, $_GET['update_stok']);
        $stok = (int)$_GET['stok'];
        
        $update_sql = "UPDATE obat SET stok = $stok WHERE Kode_obat = '$kode'";
        if (mysqli_query($conn, $update_sql)) {
            echo "alert('Stok berhasil diupdate!'); window.location.href = 'obat_list.php';";
        } else {
            echo "alert('Error: " . mysqli_error($conn) . "');";
        }
    }
    ?>
    </script>
</body>
</html>
