<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Obat - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Sistem Manajemen Obat</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="obat_list.php">💊 Daftar Obat</a>
            <a href="pasien_form.php">👤 Tambah Pasien</a>
            <a href="rekam_medis_form.php">📋 Input Rekam Medis</a>
        </div>

        <div class="card">
            <h2>💊 Form Tambah Obat</h2>
              <?php
            if (isset($_POST['simpan'])) {
                $kode_obat = mysqli_real_escape_string($conn, $_POST['kode_obat']);
                $nama = mysqli_real_escape_string($conn, $_POST['nm_obat']);
                $harga = (float)$_POST['harga_obat'];
                $tgl_kadaluarsa = $_POST['tgl_kadaluarsa'];
                $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
                $letak = mysqli_real_escape_string($conn, $_POST['letak_obat']);
                $stok = (int)$_POST['stok'];
                
                $sql = "INSERT INTO obat (Kode_obat, nm_obat, harga_obat, tgl_kadaluarsa, satuan, letak_obat, stok) 
                        VALUES ('$kode_obat', '$nama', '$harga', '$tgl_kadaluarsa', '$satuan', '$letak', '$stok')";
                if (mysqli_query($conn, $sql)) {
                    echo '<div class="alert alert-success">✅ Obat berhasil ditambah!</div>';
                } else {
                    echo '<div class="alert alert-error">❌ Error: ' . mysqli_error($conn) . '</div>';
                }
            }
            ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="kode_obat">Kode Obat:</label>
                    <input type="text" name="kode_obat" class="form-control" required placeholder="Contoh: OBT001" maxlength="10">
                </div>
                
                <div class="form-group">
                    <label for="nm_obat">Nama Obat:</label>
                    <input type="text" name="nm_obat" class="form-control" required placeholder="Masukkan nama obat">
                </div>
                
                <div class="form-group">
                    <label for="harga_obat">Harga Obat (Rp):</label>
                    <input type="number" name="harga_obat" class="form-control" required min="0" step="0.01" placeholder="Harga dalam Rupiah">
                </div>
                
                <div class="form-group">
                    <label for="tgl_kadaluarsa">Tanggal Kadaluarsa:</label>
                    <input type="date" name="tgl_kadaluarsa" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="satuan">Satuan:</label>
                    <select name="satuan" class="form-control" required>
                        <option value="">-- Pilih Satuan --</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Kapsul">Kapsul</option>
                        <option value="Botol">Botol</option>
                        <option value="Tube">Tube</option>
                        <option value="Strip">Strip</option>
                        <option value="Vial">Vial</option>
                        <option value="ml">ml</option>
                        <option value="mg">mg</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="letak_obat">Letak Obat:</label>
                    <input type="text" name="letak_obat" class="form-control" required placeholder="Contoh: Rak A1">
                </div>
                
                <div class="form-group">
                    <label for="stok">Stok:</label>
                    <input type="number" name="stok" class="form-control" required min="0" placeholder="Jumlah stok obat">
                </div>
                
                <button type="submit" name="simpan" class="btn btn-primary">💾 Simpan Obat</button>
                <a href="obat_list.php" class="btn btn-success">💊 Lihat Daftar Obat</a>
            </form>
        </div>
    </div>
</body>
</html>