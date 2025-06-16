<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Pasien - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Sistem Informasi Manajemen Pasien</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
            <a href="rekam_medis_form.php">📋 Input Rekam Medis</a>
            <a href="dokter_list.php">👨‍⚕️ Daftar Dokter</a>
        </div>

        <div class="card">
            <h2>👤 Form Input Data Pasien</h2>
              <?php
            if (isset($_POST['simpan'])) {
                $jenis_pasien = mysqli_real_escape_string($conn, $_POST['jenis_pasien']);
                $nama = mysqli_real_escape_string($conn, $_POST['nama']);
                $tgl_masuk = $_POST['tgl_masuk'];
                $tmpt_lahir = mysqli_real_escape_string($conn, $_POST['tmpt_lahir']);
                $tgl_lahir = $_POST['tgl_lahir'];
                $jk = $_POST['jk'];
                $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
                $tlpn = mysqli_real_escape_string($conn, $_POST['tlpn']);
                
                // Hitung umur
                $lahir = new DateTime($tgl_lahir);
                $sekarang = new DateTime();
                $umur = $sekarang->diff($lahir)->y;
                
                $sql = "INSERT INTO pasien (Jenis_Pasien, Nm_Pasien, Tgl_Masuk, Tmpt_Lahir, Tgl_lahir, Umur, JK, Alamat, Tlpn) 
                        VALUES ('$jenis_pasien', '$nama', '$tgl_masuk', '$tmpt_lahir', '$tgl_lahir', '$umur', '$jk', '$alamat', '$tlpn')";
                
                if (mysqli_query($conn, $sql)) {
                    echo '<div class="alert alert-success">✅ Data pasien berhasil disimpan!</div>';
                } else {
                    echo '<div class="alert alert-error">❌ Error: ' . mysqli_error($conn) . '</div>';
                }
            }
            ?>
              <form method="POST" action="">
                <div class="form-group">
                    <label for="jenis_pasien">Jenis Pasien:</label>
                    <select name="jenis_pasien" class="form-control" required>
                        <option value="">-- Pilih Jenis Pasien --</option>
                        <option value="Rawat Jalan">Rawat Jalan</option>
                        <option value="Rawat Inap">Rawat Inap</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap pasien">
                </div>
                
                <div class="form-group">
                    <label for="tgl_masuk">Tanggal Masuk:</label>
                    <input type="date" name="tgl_masuk" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label for="tmpt_lahir">Tempat Lahir:</label>
                    <input type="text" name="tmpt_lahir" class="form-control" required placeholder="Masukkan tempat lahir">
                </div>
                
                <div class="form-group">
                    <label for="tgl_lahir">Tanggal Lahir:</label>
                    <input type="date" name="tgl_lahir" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="jk">Jenis Kelamin:</label>
                    <select name="jk" class="form-control" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap:</label>
                    <textarea name="alamat" class="form-control" required placeholder="Masukkan alamat lengkap pasien" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tlpn">Nomor Telepon:</label>
                    <input type="tel" name="tlpn" class="form-control" placeholder="Masukkan nomor telepon">
                </div>
                
                <button type="submit" name="simpan" class="btn btn-primary">💾 Simpan Data Pasien</button>
                <a href="pasien_list.php" class="btn btn-success">👥 Lihat Daftar Pasien</a>
            </form>
        </div>
    </div>
</body>
</html>