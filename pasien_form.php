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
                $nama = mysqli_real_escape_string($conn, $_POST['nama']);
                $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
                $tgl = $_POST['tgl_lahir'];
                $jenis_kelamin = $_POST['jenis_kelamin'];
                $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
                $no_ktp = mysqli_real_escape_string($conn, $_POST['no_ktp']);
                
                $sql = "INSERT INTO pasien (nama, alamat, tgl_lahir, jenis_kelamin, no_telepon, no_ktp) 
                        VALUES ('$nama', '$alamat', '$tgl', '$jenis_kelamin', '$no_telepon', '$no_ktp')";
                
                if (mysqli_query($conn, $sql)) {
                    echo '<div class="alert alert-success">✅ Data pasien berhasil disimpan!</div>';
                } else {
                    echo '<div class="alert alert-error">❌ Error: ' . mysqli_error($conn) . '</div>';
                }
            }
            ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap pasien">
                </div>
                
                <div class="form-group">
                    <label for="no_ktp">Nomor KTP:</label>
                    <input type="text" name="no_ktp" class="form-control" required placeholder="Masukkan nomor KTP" maxlength="16">
                </div>
                
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin:</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tgl_lahir">Tanggal Lahir:</label>
                    <input type="date" name="tgl_lahir" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap:</label>
                    <textarea name="alamat" class="form-control" required placeholder="Masukkan alamat lengkap pasien" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="no_telepon">Nomor Telepon:</label>
                    <input type="tel" name="no_telepon" class="form-control" required placeholder="Masukkan nomor telepon">
                </div>
                
                <button type="submit" name="simpan" class="btn btn-primary">💾 Simpan Data Pasien</button>
                <a href="pasien_list.php" class="btn btn-success">👥 Lihat Daftar Pasien</a>
            </form>
        </div>
    </div>
</body>
</html>