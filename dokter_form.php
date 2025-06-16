<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokter - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏥</text></svg>">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Sistem Manajemen Data Dokter</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="dokter_list.php">👨‍⚕️ Daftar Dokter</a>
            <a href="rekam_medis_form.php">📋 Input Rekam Medis</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
        </div>

        <div class="card">
            <h2>👨‍⚕️ Form Tambah Dokter</h2>
            
            <?php
            if (isset($_POST['simpan'])) {
                $nm_dokter = sanitize_input($_POST['nm_dokter']);
                $jk = $_POST['jk'];
                $status = $_POST['status'];
                $tgl_lahir = $_POST['tgl_lahir'];
                $tempat_lahir = sanitize_input($_POST['tempat_lahir']);
                $pendidikan = sanitize_input($_POST['pendidikan']);
                $alamat = sanitize_input($_POST['alamat']);
                $kode_keahlian = $_POST['kode_keahlian'];
                
                $sql = "INSERT INTO dokter (nm_dokter, JK, status, tgl_lahir, tempat_lahir, pendidikan, alamat, Kode_Keahlian) 
                        VALUES ('$nm_dokter', '$jk', '$status', '$tgl_lahir', '$tempat_lahir', '$pendidikan', '$alamat', '$kode_keahlian')";
                
                if (mysqli_query($conn, $sql)) {
                    echo '<div class="alert alert-success">✅ Data dokter berhasil disimpan!</div>';
                } else {
                    echo '<div class="alert alert-error">❌ Error: ' . mysqli_error($conn) . '</div>';
                }
            }
            ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nm_dokter">Nama Dokter:</label>
                    <input type="text" name="nm_dokter" class="form-control" required placeholder="Masukkan nama lengkap dokter">
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
                    <label for="status">Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Aktif" selected>Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tgl_lahir">Tanggal Lahir:</label>
                    <input type="date" name="tgl_lahir" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="tempat_lahir">Tempat Lahir:</label>
                    <input type="text" name="tempat_lahir" class="form-control" required placeholder="Masukkan tempat lahir">
                </div>
                
                <div class="form-group">
                    <label for="pendidikan">Pendidikan:</label>
                    <input type="text" name="pendidikan" class="form-control" required placeholder="Contoh: S1 Kedokteran UI">
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap:</label>
                    <textarea name="alamat" class="form-control" required placeholder="Masukkan alamat lengkap dokter" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="kode_keahlian">Spesialisasi:</label>
                    <select name="kode_keahlian" class="form-control" required>
                        <option value="">-- Pilih Spesialisasi --</option>
                        <?php
                        $result_keahlian = mysqli_query($conn, "SELECT * FROM jenis_keahlian ORDER BY Nmbidang_keahlian");
                        while ($keahlian = mysqli_fetch_assoc($result_keahlian)) {
                            echo "<option value='".$keahlian['Kode_keahlian']."'>".$keahlian['Nmbidang_keahlian']."</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <button type="submit" name="simpan" class="btn btn-primary">💾 Simpan Data Dokter</button>
                <a href="dokter_list.php" class="btn btn-success">👨‍⚕️ Lihat Daftar Dokter</a>
            </form>
        </div>
    </div>
</body>
</html>
