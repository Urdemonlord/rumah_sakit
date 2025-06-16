<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Rawat Inap - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏥</text></svg>">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Daftar Pasien Rawat Inap</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="rawat_inap_form.php">➕ Input Rawat Inap</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
            <a href="dokter_list.php">👨‍⚕️ Daftar Dokter</a>
        </div>

        <div class="card">
            <h2>🏥 Daftar Rawat Inap</h2>
            
            <?php
            $sql = "SELECT di.*, p.Nm_Pasien, p.JK, p.Umur, p.Tlpn, dr.nm_Rinap, dr.Tarif
                    FROM detail_inap di 
                    LEFT JOIN pasien p ON di.Id_Pasien = p.Id_Pasien 
                    LEFT JOIN data_ruang dr ON di.kelas = dr.Kelas
                    ORDER BY di.tgl_inap DESC";
            $result = mysqli_query($conn, $sql);
            $total_rawat_inap = 0;
            $masih_dirawat = 0;
            
            if ($result) {
                $total_rawat_inap = mysqli_num_rows($result);
                // Hitung yang masih dirawat
                mysqli_data_seek($result, 0);
                while ($row = mysqli_fetch_assoc($result)) {
                    if (is_null($row['Tgl_keluarinap'])) {
                        $masih_dirawat++;
                    }
                }
                mysqli_data_seek($result, 0); // Reset pointer
            }
            ?>
            
            <div class="alert alert-info">
                📊 Total Rawat Inap: <strong><?php echo $total_rawat_inap; ?></strong> record | 
                🏥 Masih Dirawat: <strong><?php echo $masih_dirawat; ?></strong> pasien
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pasien</th>
                        <th>Nama Pasien</th>
                        <th>JK</th>
                        <th>Umur</th>
                        <th>Kelas/Ruang</th>
                        <th>Tarif/Hari</th>
                        <th>Tgl Masuk</th>
                        <th>Tgl Keluar</th>
                        <th>Lama Inap</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $jk_full = ($row['JK'] == 'L') ? 'Laki-laki' : 'Perempuan';
                            $status = is_null($row['Tgl_keluarinap']) ? 'Masih Dirawat' : 'Sudah Keluar';
                            $status_badge = is_null($row['Tgl_keluarinap']) ? 
                                '<span style="background: #f39c12; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">🏥 Masih Dirawat</span>' :
                                '<span style="background: #27ae60; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">✅ Sudah Keluar</span>';
                            
                            echo "<tr>
                                <td>".$no++."</td>
                                <td><strong>P".str_pad($row['Id_Pasien'], 4, '0', STR_PAD_LEFT)."</strong></td>
                                <td><strong>".$row['Nm_Pasien']."</strong></td>
                                <td>".$jk_full."</td>
                                <td>".$row['Umur']." tahun</td>
                                <td><span style='background: #3498db; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;'>".$row['nm_Rinap']."</span></td>
                                <td>".format_currency($row['Tarif'])."</td>
                                <td>".format_date($row['tgl_inap'])."</td>
                                <td>".($row['Tgl_keluarinap'] ? format_date($row['Tgl_keluarinap']) : '-')."</td>
                                <td>".($row['lama_inap'] ? $row['lama_inap'].' hari' : '-')."</td>
                                <td>".($row['total_Inap'] ? format_currency($row['total_Inap']) : '-')."</td>
                                <td>".$status_badge."</td>
                                <td>";
                            
                            if (is_null($row['Tgl_keluarinap'])) {
                                echo "<a href='?checkout=".$row['Id_Pasien']."&kelas=".$row['kelas']."&tgl_inap=".$row['tgl_inap']."' class='btn btn-primary btn-sm' onclick='return confirm(\"Yakin ingin checkout pasien ini?\")'>🚪 Check Out</a>";
                            } else {
                                echo "<a href='struk_rawat_inap.php?id=".$row['Id_Pasien']."&kelas=".$row['kelas']."&tgl_inap=".$row['tgl_inap']."' class='btn btn-success btn-sm'>🖨️ Cetak Struk</a>";
                            }
                            
                            echo "</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='13' style='text-align: center; color: #7f8c8d;'>Belum ada data rawat inap. <a href='rawat_inap_form.php'>Input rawat inap pertama</a></td></tr>";
                    }
                    
                    // Handle checkout
                    if (isset($_GET['checkout'])) {
                        $id_pasien = (int)$_GET['id_pasien'];
                        $kelas = sanitize_input($_GET['kelas']);
                        $tgl_inap = $_GET['tgl_inap'];
                        $tgl_keluar = date('Y-m-d');
                        
                        // Hitung lama inap dan total biaya
                        $date1 = new DateTime($tgl_inap);
                        $date2 = new DateTime($tgl_keluar);
                        $lama_inap = $date2->diff($date1)->days + 1;
                        
                        // Ambil tarif kelas
                        $result_tarif = mysqli_query($conn, "SELECT Tarif FROM data_ruang WHERE Kelas = '$kelas'");
                        $tarif_row = mysqli_fetch_assoc($result_tarif);
                        $total_inap = $lama_inap * $tarif_row['Tarif'];
                        
                        $update_sql = "UPDATE detail_inap SET 
                                      Tgl_keluarinap = '$tgl_keluar',
                                      lama_inap = $lama_inap,
                                      total_Inap = $total_inap
                                      WHERE Id_Pasien = $id_pasien AND kelas = '$kelas' AND tgl_inap = '$tgl_inap'";
                        
                        if (mysqli_query($conn, $update_sql)) {
                            // Update jenis pasien menjadi rawat jalan jika tidak ada rawat inap lain yang aktif
                            $check_aktif = mysqli_query($conn, "SELECT * FROM detail_inap WHERE Id_Pasien = $id_pasien AND Tgl_keluarinap IS NULL");
                            if (mysqli_num_rows($check_aktif) == 0) {
                                mysqli_query($conn, "UPDATE pasien SET Jenis_Pasien = 'Rawat Jalan' WHERE Id_Pasien = $id_pasien");
                            }
                            
                            echo "<script>alert('✅ Checkout berhasil! Total biaya: ".format_currency($total_inap)."'); window.location.href='rawat_inap_list.php';</script>";
                        } else {
                            echo "<script>alert('❌ Error checkout: " . mysqli_error($conn) . "');</script>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                <a href="rawat_inap_form.php" class="btn btn-primary">➕ Input Rawat Inap Baru</a>
                <a href="index.php" class="btn btn-success">🏠 Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
