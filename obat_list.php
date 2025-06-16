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
            $result = mysqli_query($conn, "SELECT * FROM obat ORDER BY nama_obat");
            $total_obat = 0;
            $stok_rendah = 0;
            if ($result) {
                $total_obat = mysqli_num_rows($result);
                // Hitung obat dengan stok rendah (< 10)
                mysqli_data_seek($result, 0);
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['stok'] < 10) {
                        $stok_rendah++;
                    }
                }
            }
            ?>
            
            <div class="alert alert-info">
                📊 Total Obat: <strong><?php echo $total_obat; ?></strong> jenis | 
                ⚠️ Stok Rendah: <strong><?php echo $stok_rendah; ?></strong> obat
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Keterangan</th>
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
                            
                            echo "<tr>
                                <td>".$no++."</td>
                                <td><strong>".$row['nama_obat']."</strong></td>
                                <td>".$row['stok']."</td>
                                <td>".($row['satuan'] ?? 'Pcs')."</td>
                                <td>Rp ".number_format($row['harga'] ?? 0, 0, ',', '.')."</td>
                                <td><span style='background: $status_color; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;'>$status_stok</span></td>
                                <td>".($row['keterangan'] ?? '-')."</td>
                                <td>
                                    <button class='btn btn-primary btn-sm' onclick='updateStok(".$row['id'].")'>📦 Update Stok</button>
                                </td>
                            </tr>";
                        }
                    }
                    
                    if ($total_obat == 0) {
                        echo "<tr><td colspan='8' style='text-align: center; color: #7f8c8d;'>Belum ada data obat. <a href='obat_form.php'>Tambah obat pertama</a></td></tr>";
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
    function updateStok(id) {
        var stok = prompt("Masukkan stok baru:");
        if (stok !== null && stok !== "") {
            window.location.href = "obat_list.php?update_stok=" + id + "&stok=" + stok;
        }
    }
    
    <?php
    // Handle update stok
    if (isset($_GET['update_stok']) && isset($_GET['stok'])) {
        $id = (int)$_GET['update_stok'];
        $stok = (int)$_GET['stok'];
        
        $update_sql = "UPDATE obat SET stok = $stok WHERE id = $id";
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