<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Rekam Medis - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Sistem Informasi Rekam Medis</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
            <a href="rekam_medis_list.php">📋 Daftar Rekam Medis</a>
            <a href="dokter_list.php">👨‍⚕️ Daftar Dokter</a>
        </div>

        <div class="card">
            <h2>📝 Form Input Rekam Medis</h2>
              <?php
            if (isset($_POST['simpan'])) {
                $id_pasien = $_POST['id_pasien'];
                $id_dokter = $_POST['id_dokter'];
                $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);
                $diagnosa = mysqli_real_escape_string($conn, $_POST['diagnosa']);
                $tb = (float)$_POST['tb'];
                $bb = (float)$_POST['bb'];
                $tensi = mysqli_real_escape_string($conn, $_POST['tensi_darah']);
                $tanggal = date('Y-m-d H:i:s');
                
                $sql = "INSERT INTO rekam_medis (id_Pasien, id_dokter, keluhan, diagnosa, TB, BB, Tensi_darah, tgl_periksa) 
                        VALUES ('$id_pasien', '$id_dokter', '$keluhan', '$diagnosa', '$tb', '$bb', '$tensi', '$tanggal')";
                
                if (mysqli_query($conn, $sql)) {
                    echo '<div class="alert alert-success">✅ Rekam medis berhasil disimpan!</div>';
                } else {
                    echo '<div class="alert alert-error">❌ Error: ' . mysqli_error($conn) . '</div>';
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
                            $selected = (isset($_GET['id_pasien']) && $_GET['id_pasien'] == $pasien['Id_Pasien']) ? 'selected' : '';
                            echo "<option value='".$pasien['Id_Pasien']."' $selected>P".str_pad($pasien['Id_Pasien'], 4, '0', STR_PAD_LEFT)." - ".$pasien['Nm_Pasien']." (".$pasien['Umur']." tahun)</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="id_dokter">Pilih Dokter:</label>
                    <select name="id_dokter" class="form-control" required>
                        <option value="">-- Pilih Dokter --</option>
                        <?php
                        $result_dokter = mysqli_query($conn, "SELECT d.*, jk.Nmbidang_keahlian FROM dokter d LEFT JOIN jenis_keahlian jk ON d.Kode_Keahlian = jk.Kode_keahlian ORDER BY d.nm_dokter");
                        while ($dokter = mysqli_fetch_assoc($result_dokter)) {
                            $selected = (isset($_GET['id_dokter']) && $_GET['id_dokter'] == $dokter['id_dokter']) ? 'selected' : '';
                            echo "<option value='".$dokter['id_dokter']."' $selected>Dr. ".$dokter['nm_dokter']." - ".$dokter['Nmbidang_keahlian']."</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="keluhan">Keluhan Pasien:</label>
                    <textarea name="keluhan" class="form-control" rows="4" required placeholder="Deskripsikan keluhan pasien..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="diagnosa">Diagnosa:</label>
                    <textarea name="diagnosa" class="form-control" rows="3" required placeholder="Masukkan diagnosa dokter..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tb">Tinggi Badan (cm):</label>
                    <input type="number" name="tb" class="form-control" step="0.1" min="0" max="300" placeholder="Contoh: 170.5">
                </div>
                
                <div class="form-group">
                    <label for="bb">Berat Badan (kg):</label>
                    <input type="number" name="bb" class="form-control" step="0.1" min="0" max="300" placeholder="Contoh: 65.5">
                </div>
                
                <div class="form-group">
                    <label for="tensi_darah">Tekanan Darah:</label>
                    <input type="text" name="tensi_darah" class="form-control" placeholder="Contoh: 120/80">
                </div>
                
                <button type="submit" name="simpan" class="btn btn-primary">💾 Simpan Rekam Medis</button>
                <a href="rekam_medis_list.php" class="btn btn-success">📋 Lihat Daftar Rekam Medis</a>
            </form>
        </div>
    </div>
</body>
</html>
