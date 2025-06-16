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
                $keluhan = $_POST['keluhan'];
                $diagnosa = $_POST['diagnosa'];
                $tindakan = $_POST['tindakan'];
                $obat = $_POST['obat'];
                $tanggal = date('Y-m-d H:i:s');
                
                $sql = "INSERT INTO rekam_medis (id_pasien, id_dokter, keluhan, diagnosa, tindakan, obat, tanggal_periksa) 
                        VALUES ('$id_pasien', '$id_dokter', '$keluhan', '$diagnosa', '$tindakan', '$obat', '$tanggal')";
                
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
                        $result_pasien = mysqli_query($conn, "SELECT * FROM pasien ORDER BY nama");
                        while ($pasien = mysqli_fetch_assoc($result_pasien)) {
                            echo "<option value='".$pasien['id']."'>".$pasien['nama']." - ".$pasien['alamat']."</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="id_dokter">Pilih Dokter:</label>
                    <select name="id_dokter" class="form-control" required>
                        <option value="">-- Pilih Dokter --</option>
                        <?php
                        $result_dokter = mysqli_query($conn, "SELECT * FROM dokter ORDER BY nama");
                        while ($dokter = mysqli_fetch_assoc($result_dokter)) {
                            echo "<option value='".$dokter['id']."'>Dr. ".$dokter['nama']." - ".$dokter['spesialis']."</option>";
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
                    <label for="tindakan">Tindakan/Terapi:</label>
                    <textarea name="tindakan" class="form-control" rows="3" required placeholder="Tindakan medis yang diberikan..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="obat">Obat yang Diberikan:</label>
                    <textarea name="obat" class="form-control" rows="3" placeholder="Daftar obat dan dosis..."></textarea>
                </div>
                
                <button type="submit" name="simpan" class="btn btn-primary">💾 Simpan Rekam Medis</button>
                <a href="rekam_medis_list.php" class="btn btn-success">📋 Lihat Daftar Rekam Medis</a>
            </form>
        </div>
    </div>
</body>
</html>
