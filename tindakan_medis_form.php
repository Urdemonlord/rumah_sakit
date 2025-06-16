<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Tindakan Medis - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏥</text></svg>">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Sistem Manajemen Tindakan Medis</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="tindakan_medis_list.php">🔧 Daftar Tindakan</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
            <a href="rekam_medis_list.php">📋 Rekam Medis</a>
        </div>

        <div class="card">
            <h2>🔧 Form Input Tindakan Medis</h2>
            
            <?php
            if (isset($_POST['simpan_tindakan'])) {
                $kode_tindak = sanitize_input($_POST['kode_tindak']);
                $nm_alat = sanitize_input($_POST['nm_alat']);
                $jenis_medik = sanitize_input($_POST['jenis_medik']);
                $jum_alat = (int)$_POST['jum_alat'];
                $harga_alat = (float)$_POST['harga_alat'];
                
                $sql = "INSERT INTO tindak_medis (kode_tindakMedis, nm_alatmedik, Jenis_medik, jum_alatmedik, harga_alatMedik) 
                        VALUES ('$kode_tindak', '$nm_alat', '$jenis_medik', '$jum_alat', '$harga_alat')";
                
                if (mysqli_query($conn, $sql)) {
                    echo '<div class="alert alert-success">✅ Data tindakan medis berhasil disimpan!</div>';
                } else {
                    echo '<div class="alert alert-error">❌ Error: ' . mysqli_error($conn) . '</div>';
                }
            }
            
            if (isset($_POST['simpan_detail'])) {
                $id_pasien = (int)$_POST['id_pasien'];
                $kode_tindak = sanitize_input($_POST['kode_tindak_detail']);
                $jum_alat = (int)$_POST['jum_alat_detail'];
                
                $sql = "INSERT INTO detail_tindak_medis (Id_Pasien, Kode_tindakMedis, Jum_alatmedik) 
                        VALUES ('$id_pasien', '$kode_tindak', '$jum_alat')";
                
                if (mysqli_query($conn, $sql)) {
                    echo '<div class="alert alert-success">✅ Detail tindakan medis untuk pasien berhasil disimpan!</div>';
                } else {
                    echo '<div class="alert alert-error">❌ Error: ' . mysqli_error($conn) . '</div>';
                }
            }
            ?>
            
            <!-- Tab Navigation -->
            <div style="margin-bottom: 20px;">
                <button class="btn btn-primary" onclick="showTab('master')">📝 Master Tindakan Medis</button>
                <button class="btn btn-success" onclick="showTab('detail')">👤 Input Tindakan untuk Pasien</button>
            </div>
            
            <!-- Master Tindakan Medis -->
            <div id="master-tab" style="display: block;">
                <h3 style="color: #3498db; margin-bottom: 15px;">📝 Master Data Tindakan Medis</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="kode_tindak">Kode Tindakan:</label>
                        <input type="text" name="kode_tindak" class="form-control" required placeholder="Contoh: TM001" maxlength="10">
                    </div>
                    
                    <div class="form-group">
                        <label for="nm_alat">Nama Alat/Tindakan Medis:</label>
                        <input type="text" name="nm_alat" class="form-control" required placeholder="Contoh: Stetoskop, USG, dll">
                    </div>
                    
                    <div class="form-group">
                        <label for="jenis_medik">Jenis Medis:</label>
                        <select name="jenis_medik" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Pemeriksaan">Pemeriksaan</option>
                            <option value="Diagnostik">Diagnostik</option>
                            <option value="Terapi">Terapi</option>
                            <option value="Bedah">Bedah</option>
                            <option value="Rehabilitasi">Rehabilitasi</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="jum_alat">Jumlah Alat Tersedia:</label>
                        <input type="number" name="jum_alat" class="form-control" required min="1" placeholder="Jumlah alat yang tersedia">
                    </div>
                    
                    <div class="form-group">
                        <label for="harga_alat">Harga/Tarif (Rp):</label>
                        <input type="number" name="harga_alat" class="form-control" required min="0" step="0.01" placeholder="Tarif penggunaan alat">
                    </div>
                    
                    <button type="submit" name="simpan_tindakan" class="btn btn-primary">💾 Simpan Master Tindakan</button>
                </form>
            </div>
            
            <!-- Detail Tindakan untuk Pasien -->
            <div id="detail-tab" style="display: none;">
                <h3 style="color: #27ae60; margin-bottom: 15px;">👤 Input Tindakan untuk Pasien</h3>
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
                        <label for="kode_tindak_detail">Pilih Tindakan Medis:</label>
                        <select name="kode_tindak_detail" class="form-control" required onchange="updateHarga()">
                            <option value="">-- Pilih Tindakan --</option>
                            <?php
                            $result_tindakan = mysqli_query($conn, "SELECT * FROM tindak_medis ORDER BY nm_alatmedik");
                            while ($tindakan = mysqli_fetch_assoc($result_tindakan)) {
                                echo "<option value='".$tindakan['kode_tindakMedis']."' data-harga='".$tindakan['harga_alatMedik']."' data-stok='".$tindakan['jum_alatmedik']."'>".$tindakan['nm_alatmedik']." - ".$tindakan['Jenis_medik']." (".format_currency($tindakan['harga_alatMedik']).")</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="jum_alat_detail">Jumlah yang Digunakan:</label>
                        <input type="number" name="jum_alat_detail" class="form-control" required min="1" placeholder="Jumlah alat yang digunakan" onchange="calculateTotal()">
                    </div>
                    
                    <div id="info-biaya" style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 15px 0; display: none;">
                        <h4 style="color: #3498db; margin-bottom: 10px;">💰 Informasi Biaya</h4>
                        <div id="tarif-tindakan"></div>
                        <div id="total-biaya-tindakan"></div>
                    </div>
                    
                    <button type="submit" name="simpan_detail" class="btn btn-success">💾 Simpan Tindakan untuk Pasien</button>
                </form>
            </div>
            
            <div style="margin-top: 20px;">
                <a href="tindakan_medis_list.php" class="btn btn-success">🔧 Lihat Daftar Tindakan</a>
                <a href="index.php" class="btn btn-primary">🏠 Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.getElementById('master-tab').style.display = tabName === 'master' ? 'block' : 'none';
            document.getElementById('detail-tab').style.display = tabName === 'detail' ? 'block' : 'none';
        }
        
        function updateHarga() {
            const select = document.querySelector('select[name="kode_tindak_detail"]');
            const selectedOption = select.options[select.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');
            const stok = selectedOption.getAttribute('data-stok');
            
            if (harga) {
                document.getElementById('tarif-tindakan').innerHTML = `<strong>Tarif:</strong> Rp ${parseInt(harga).toLocaleString('id-ID')} | <strong>Tersedia:</strong> ${stok} unit`;
                calculateTotal();
            }
        }
        
        function calculateTotal() {
            const select = document.querySelector('select[name="kode_tindak_detail"]');
            const selectedOption = select.options[select.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');
            const jumlah = document.querySelector('input[name="jum_alat_detail"]').value;
            
            if (harga && jumlah) {
                const total = parseInt(harga) * parseInt(jumlah);
                document.getElementById('total-biaya-tindakan').innerHTML = `<strong>Total Biaya:</strong> Rp ${total.toLocaleString('id-ID')}`;
                document.getElementById('info-biaya').style.display = 'block';
            } else {
                document.getElementById('info-biaya').style.display = 'none';
            }
        }
    </script>
</body>
</html>
