<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Rawat Inap - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏥</text></svg>">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Sistem Manajemen Rawat Inap</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="rawat_inap_list.php">🏥 Daftar Rawat Inap</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
            <a href="dokter_list.php">👨‍⚕️ Daftar Dokter</a>
        </div>

        <div class="card">
            <h2>🏥 Form Input Rawat Inap</h2>
            
            <?php
            if (isset($_POST['simpan'])) {
                $id_pasien = (int)$_POST['id_pasien'];
                $kelas = sanitize_input($_POST['kelas']);
                $tgl_inap = $_POST['tgl_inap'];
                $tgl_keluar = !empty($_POST['tgl_keluar']) ? $_POST['tgl_keluar'] : NULL;
                
                // Hitung lama inap dan total biaya jika ada tanggal keluar
                $lama_inap = NULL;
                $total_inap = NULL;
                
                if ($tgl_keluar) {
                    $date1 = new DateTime($tgl_inap);
                    $date2 = new DateTime($tgl_keluar);
                    $lama_inap = $date2->diff($date1)->days + 1; // +1 untuk menghitung hari check-in
                    
                    // Ambil tarif kelas
                    $result_tarif = mysqli_query($conn, "SELECT Tarif FROM data_ruang WHERE Kelas = '$kelas'");
                    if ($tarif_row = mysqli_fetch_assoc($result_tarif)) {
                        $total_inap = $lama_inap * $tarif_row['Tarif'];
                    }
                }
                
                // Cek apakah sudah ada data rawat inap untuk pasien di kelas yang sama
                $check_sql = "SELECT * FROM detail_inap WHERE Id_Pasien = $id_pasien AND kelas = '$kelas' AND Tgl_keluarinap IS NULL";
                $check_result = mysqli_query($conn, $check_sql);
                
                if (mysqli_num_rows($check_result) > 0) {
                    echo '<div class="alert alert-error">❌ Pasien sudah dalam rawat inap di kelas yang sama!</div>';
                } else {
                    $sql = "INSERT INTO detail_inap (Id_Pasien, kelas, tgl_inap, Tgl_keluarinap, total_Inap, lama_inap) 
                            VALUES ($id_pasien, '$kelas', '$tgl_inap', " . ($tgl_keluar ? "'$tgl_keluar'" : "NULL") . ", " . ($total_inap ? $total_inap : "NULL") . ", " . ($lama_inap ? $lama_inap : "NULL") . ")";
                    
                    if (mysqli_query($conn, $sql)) {
                        // Update jenis pasien menjadi rawat inap
                        mysqli_query($conn, "UPDATE pasien SET Jenis_Pasien = 'Rawat Inap' WHERE Id_Pasien = $id_pasien");
                        echo '<div class="alert alert-success">✅ Data rawat inap berhasil disimpan!</div>';
                    } else {
                        echo '<div class="alert alert-error">❌ Error: ' . mysqli_error($conn) . '</div>';
                    }
                }
            }
            ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="id_pasien">Pilih Pasien:</label>
                    <select name="id_pasien" class="form-control" required>
                        <option value="">-- Pilih Pasien --</option>
                        <?php
                        $result_pasien = mysqli_query($conn, "SELECT * FROM pasien ORDER BY Nm_Pasien");
                        while ($pasien = mysqli_fetch_assoc($result_pasien)) {
                            echo "<option value='".$pasien['Id_Pasien']."'>P".str_pad($pasien['Id_Pasien'], 4, '0', STR_PAD_LEFT)." - ".$pasien['Nm_Pasien']." (".$pasien['Umur']." tahun)</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="kelas">Pilih Kelas Ruang:</label>
                    <select name="kelas" class="form-control" required onchange="updateTarif()">
                        <option value="">-- Pilih Kelas Ruang --</option>
                        <?php
                        $result_ruang = mysqli_query($conn, "SELECT * FROM data_ruang ORDER BY Kelas");
                        while ($ruang = mysqli_fetch_assoc($result_ruang)) {
                            echo "<option value='".$ruang['Kelas']."' data-tarif='".$ruang['Tarif']."'>".$ruang['nm_Rinap']." - ".format_currency($ruang['Tarif'])."/hari (Tersedia: ".$ruang['Jum_RInap']." kamar)</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tgl_inap">Tanggal Masuk Rawat Inap:</label>
                    <input type="date" name="tgl_inap" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label for="tgl_keluar">Tanggal Keluar (Opsional):</label>
                    <input type="date" name="tgl_keluar" class="form-control" onchange="calculateTotal()">
                    <small style="color: #7f8c8d;">Kosongkan jika pasien masih rawat inap</small>
                </div>
                
                <div id="biaya-info" style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 15px 0; display: none;">
                    <h4 style="color: #3498db; margin-bottom: 10px;">💰 Informasi Biaya</h4>
                    <div id="tarif-display"></div>
                    <div id="lama-inap-display"></div>
                    <div id="total-biaya-display"></div>
                </div>
                
                <button type="submit" name="simpan" class="btn btn-primary">💾 Simpan Data Rawat Inap</button>
                <a href="rawat_inap_list.php" class="btn btn-success">🏥 Lihat Daftar Rawat Inap</a>
            </form>
        </div>
    </div>

    <script>
        function updateTarif() {
            const kelasSelect = document.querySelector('select[name="kelas"]');
            const selectedOption = kelasSelect.options[kelasSelect.selectedIndex];
            const tarif = selectedOption.getAttribute('data-tarif');
            
            if (tarif) {
                document.getElementById('tarif-display').innerHTML = `<strong>Tarif per hari:</strong> Rp ${parseInt(tarif).toLocaleString('id-ID')}`;
                calculateTotal();
            }
        }
        
        function calculateTotal() {
            const tglInap = document.querySelector('input[name="tgl_inap"]').value;
            const tglKeluar = document.querySelector('input[name="tgl_keluar"]').value;
            const kelasSelect = document.querySelector('select[name="kelas"]');
            const selectedOption = kelasSelect.options[kelasSelect.selectedIndex];
            const tarif = selectedOption.getAttribute('data-tarif');
            
            if (tglInap && tglKeluar && tarif) {
                const date1 = new Date(tglInap);
                const date2 = new Date(tglKeluar);
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                const totalBiaya = diffDays * parseInt(tarif);
                
                document.getElementById('lama-inap-display').innerHTML = `<strong>Lama rawat inap:</strong> ${diffDays} hari`;
                document.getElementById('total-biaya-display').innerHTML = `<strong>Total biaya:</strong> Rp ${totalBiaya.toLocaleString('id-ID')}`;
                document.getElementById('biaya-info').style.display = 'block';
            } else if (tarif) {
                document.getElementById('lama-inap-display').innerHTML = '';
                document.getElementById('total-biaya-display').innerHTML = '<em>Total akan dihitung saat pasien keluar</em>';
                document.getElementById('biaya-info').style.display = 'block';
            } else {
                document.getElementById('biaya-info').style.display = 'none';
            }
        }
    </script>
</body>
</html>
