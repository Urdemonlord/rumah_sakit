<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Resep Obat - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Sistem Resep Obat Digital</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="resep_list.php">📋 Daftar Resep</a>
            <a href="obat_list.php">💊 Daftar Obat</a>
            <a href="rekam_medis_form.php">📝 Rekam Medis</a>
        </div>

        <div class="card">
            <h2>💊 Form Resep Obat</h2>
            
            <?php
            if (isset($_POST['simpan_resep'])) {
                $kode_resep = mysqli_real_escape_string($conn, $_POST['kode_resep']);
                $id_pasien = (int)$_POST['id_pasien'];
                
                // Insert resep obat
                $sql_resep = "INSERT INTO resep_obat (Kode_Resep, Id_Pasien) VALUES ('$kode_resep', '$id_pasien')";
                
                if (mysqli_query($conn, $sql_resep)) {
                    // Insert detail obat
                    $obat_list = $_POST['obat_list'];
                    $jumlah_list = $_POST['jumlah_list'];
                    $aturan_list = $_POST['aturan_list'];
                    
                    $success = true;
                    for ($i = 0; $i < count($obat_list); $i++) {
                        if (!empty($obat_list[$i])) {
                            $kode_obat = mysqli_real_escape_string($conn, $obat_list[$i]);
                            $jumlah = (int)$jumlah_list[$i];
                            $aturan = mysqli_real_escape_string($conn, $aturan_list[$i]);
                            
                            $sql_detail = "INSERT INTO detail_obat (Kode_Resep, kode_obat, Juml_obat, aturan_Pakai, id_Pasien) 
                                          VALUES ('$kode_resep', '$kode_obat', '$jumlah', '$aturan', '$id_pasien')";
                            
                            if (!mysqli_query($conn, $sql_detail)) {
                                $success = false;
                                break;
                            }
                            
                            // Update stok obat
                            $sql_update_stok = "UPDATE obat SET stok = stok - $jumlah WHERE Kode_obat = '$kode_obat'";
                            mysqli_query($conn, $sql_update_stok);
                        }
                    }
                    
                    if ($success) {
                        echo '<div class="alert alert-success">✅ Resep obat berhasil disimpan!</div>';
                    } else {
                        echo '<div class="alert alert-error">❌ Error saat menyimpan detail obat</div>';
                    }
                } else {
                    echo '<div class="alert alert-error">❌ Error: ' . mysqli_error($conn) . '</div>';
                }
            }
            
            // Generate kode resep otomatis
            $kode_resep = 'RSP' . date('ymd') . rand(100, 999);
            ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="kode_resep">Kode Resep:</label>
                    <input type="text" name="kode_resep" class="form-control" value="<?php echo $kode_resep; ?>" readonly>
                </div>
                
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
                
                <h3>📋 Daftar Obat</h3>
                <div id="obat-container">
                    <div class="obat-row" style="display: grid; grid-template-columns: 2fr 1fr 2fr 50px; gap: 10px; margin-bottom: 10px; align-items: end;">
                        <div class="form-group">
                            <label>Pilih Obat:</label>
                            <select name="obat_list[]" class="form-control" required>
                                <option value="">-- Pilih Obat --</option>
                                <?php
                                $result_obat = mysqli_query($conn, "SELECT * FROM obat WHERE stok > 0 ORDER BY nm_obat");
                                while ($obat = mysqli_fetch_assoc($result_obat)) {
                                    echo "<option value='".$obat['Kode_obat']."'>".$obat['nm_obat']." (Stok: ".$obat['stok']." ".$obat['satuan'].")</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jumlah:</label>
                            <input type="number" name="jumlah_list[]" class="form-control" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Aturan Pakai:</label>
                            <input type="text" name="aturan_list[]" class="form-control" placeholder="Contoh: 3x1 tablet setelah makan" required>
                        </div>
                        <button type="button" onclick="removeObat(this)" class="btn btn-danger btn-sm">❌</button>
                    </div>
                </div>
                
                <button type="button" onclick="addObat()" class="btn btn-success">➕ Tambah Obat</button>
                
                <hr>
                
                <button type="submit" name="simpan_resep" class="btn btn-primary">💾 Simpan Resep</button>
                <a href="resep_list.php" class="btn btn-success">📋 Lihat Daftar Resep</a>
            </form>
        </div>
    </div>
    
    <script>
    function addObat() {
        const container = document.getElementById('obat-container');
        const newRow = container.firstElementChild.cloneNode(true);
        
        // Reset values
        const selects = newRow.querySelectorAll('select');
        const inputs = newRow.querySelectorAll('input');
        
        selects.forEach(select => select.selectedIndex = 0);
        inputs.forEach(input => input.value = '');
        
        container.appendChild(newRow);
    }
    
    function removeObat(button) {
        const container = document.getElementById('obat-container');
        if (container.children.length > 1) {
            button.parentElement.remove();
        } else {
            alert('Minimal harus ada satu obat');
        }
    }
    </script>
</body>
</html>
