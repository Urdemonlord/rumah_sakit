<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Rumah Sakit</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏥</text></svg>">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Rumah Sakit Modern</h1>
            <p>Laporan dan Analisis Data</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Dashboard</a>
            <a href="struk_cetak.php">🖨️ Cetak Struk</a>
            <a href="rekam_medis_list.php">📋 Rekam Medis</a>
            <a href="pasien_list.php">👥 Daftar Pasien</a>
        </div>

        <!-- Summary Statistics -->
        <div class="card">
            <h2>📊 Statistik Keseluruhan</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <?php
                $stats = [
                    ['icon' => '👥', 'title' => 'Total Pasien', 'value' => mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM pasien"))[0], 'color' => '#3498db'],
                    ['icon' => '👨‍⚕️', 'title' => 'Total Dokter', 'value' => mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM dokter WHERE status = 'Aktif'"))[0], 'color' => '#27ae60'],
                    ['icon' => '💊', 'title' => 'Jenis Obat', 'value' => mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM obat"))[0], 'color' => '#e74c3c'],
                    ['icon' => '📋', 'title' => 'Rekam Medis', 'value' => mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM rekam_medis"))[0], 'color' => '#f39c12'],
                    ['icon' => '🏥', 'title' => 'Rawat Inap Aktif', 'value' => mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM detail_inap WHERE Tgl_keluarinap IS NULL"))[0], 'color' => '#9b59b6'],
                    ['icon' => '💊', 'title' => 'Total Resep', 'value' => mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM resep_obat"))[0], 'color' => '#1abc9c']
                ];
                
                foreach ($stats as $stat) {
                    echo "<div style='background: ".$stat['color']."; color: white; padding: 20px; border-radius: 15px; text-align: center;'>
                            <div style='font-size: 2rem; margin-bottom: 10px;'>".$stat['icon']."</div>
                            <h3 style='margin: 0; font-size: 1.8rem;'>".$stat['value']."</h3>
                            <p style='margin: 5px 0 0; opacity: 0.9;'>".$stat['title']."</p>
                          </div>";
                }
                ?>
            </div>
        </div>

        <!-- Laporan Harian -->
        <div class="card">
            <h2>📅 Laporan Hari Ini (<?php echo date('d F Y'); ?>)</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <h4 style="color: #3498db; margin-bottom: 15px;">📋 Rekam Medis Hari Ini</h4>
                    <?php
                    $rekam_hari_ini = mysqli_query($conn, "SELECT rm.*, p.Nm_Pasien, d.nm_dokter 
                                                           FROM rekam_medis rm 
                                                           LEFT JOIN pasien p ON rm.id_Pasien = p.Id_Pasien 
                                                           LEFT JOIN dokter d ON rm.id_dokter = d.id_dokter 
                                                           WHERE DATE(rm.tgl_periksa) = CURDATE() 
                                                           ORDER BY rm.tgl_periksa DESC");
                   $total_rekam_hari_ini = mysqli_num_rows($rekam_hari_ini);
                    ?>
                    <p><strong>Total: <?php echo $total_rekam_hari_ini; ?> pasien</strong></p>
                    
                    <?php if ($total_rekam_hari_ini > 0): ?>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <?php while ($rekam = mysqli_fetch_assoc($rekam_hari_ini)): ?>
                        <div style="background: #f8f9fa; padding: 10px; margin-bottom: 8px; border-radius: 8px; border-left: 4px solid #3498db;">
                            <strong><?php echo $rekam['Nm_Pasien']; ?></strong><br>
                            <small style="color: #7f8c8d;">Dr. <?php echo $rekam['nm_dokter']; ?> - <?php echo format_datetime($rekam['tgl_periksa']); ?></small><br>
                            <small><strong>Diagnosa:</strong> <?php echo substr($rekam['diagnosa'], 0, 50) . (strlen($rekam['diagnosa']) > 50 ? '...' : ''); ?></small>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p style="color: #7f8c8d; font-style: italic;">Belum ada rekam medis hari ini</p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <h4 style="color: #27ae60; margin-bottom: 15px;">💊 Resep Hari Ini</h4>
                    <?php
                    $resep_hari_ini = mysqli_query($conn, "SELECT ro.*, p.Nm_Pasien 
                                                           FROM resep_obat ro 
                                                           LEFT JOIN pasien p ON ro.Id_Pasien = p.Id_Pasien 
                                                           WHERE DATE(ro.tgl_resep) = CURDATE() 
                                                           ORDER BY ro.tgl_resep DESC");
                    $total_resep_hari_ini = mysqli_num_rows($resep_hari_ini);
                    ?>
                    <p><strong>Total: <?php echo $total_resep_hari_ini; ?> resep</strong></p>
                    
                    <?php if ($total_resep_hari_ini > 0): ?>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <?php while ($resep = mysqli_fetch_assoc($resep_hari_ini)): ?>
                        <div style="background: #f8f9fa; padding: 10px; margin-bottom: 8px; border-radius: 8px; border-left: 4px solid #27ae60;">
                            <strong><?php echo $resep['Kode_Resep']; ?></strong><br>
                            <small style="color: #7f8c8d;"><?php echo $resep['Nm_Pasien']; ?> - <?php echo format_datetime($resep['tgl_resep']); ?></small>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p style="color: #7f8c8d; font-style: italic;">Belum ada resep hari ini</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Laporan Obat -->
        <div class="card">
            <h2>💊 Laporan Stok Obat</h2>
            <?php
            $stok_rendah = mysqli_query($conn, "SELECT * FROM obat WHERE stok < 10 ORDER BY stok ASC");
            $akan_kadaluarsa = mysqli_query($conn, "SELECT * FROM obat WHERE tgl_kadaluarsa <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND tgl_kadaluarsa > CURDATE() ORDER BY tgl_kadaluarsa ASC");
            $sudah_kadaluarsa = mysqli_query($conn, "SELECT * FROM obat WHERE tgl_kadaluarsa <= CURDATE() ORDER BY tgl_kadaluarsa ASC");
            ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <!-- Stok Rendah -->
                <div>
                    <h4 style="color: #e74c3c; margin-bottom: 15px;">⚠️ Stok Rendah (< 10)</h4>
                    <p><strong>Total: <?php echo mysqli_num_rows($stok_rendah); ?> obat</strong></p>
                    <?php if (mysqli_num_rows($stok_rendah) > 0): ?>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <?php while ($obat = mysqli_fetch_assoc($stok_rendah)): ?>
                        <div style="background: #fee; padding: 8px; margin-bottom: 5px; border-radius: 5px; border-left: 3px solid #e74c3c;">
                            <strong><?php echo $obat['nm_obat']; ?></strong><br>
                            <small>Stok: <?php echo $obat['stok']; ?> <?php echo $obat['satuan']; ?></small>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p style="color: #27ae60; font-style: italic;">✅ Semua stok obat mencukupi</p>
                    <?php endif; ?>
                </div>
                
                <!-- Akan Kadaluarsa -->
                <div>
                    <h4 style="color: #f39c12; margin-bottom: 15px;">⏰ Akan Kadaluarsa (30 hari)</h4>
                    <p><strong>Total: <?php echo mysqli_num_rows($akan_kadaluarsa); ?> obat</strong></p>
                    <?php if (mysqli_num_rows($akan_kadaluarsa) > 0): ?>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <?php while ($obat = mysqli_fetch_assoc($akan_kadaluarsa)): ?>
                        <div style="background: #fff3cd; padding: 8px; margin-bottom: 5px; border-radius: 5px; border-left: 3px solid #f39c12;">
                            <strong><?php echo $obat['nm_obat']; ?></strong><br>
                            <small>Kadaluarsa: <?php echo format_date($obat['tgl_kadaluarsa']); ?></small>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p style="color: #27ae60; font-style: italic;">✅ Tidak ada obat yang akan kadaluarsa</p>
                    <?php endif; ?>
                </div>
                
                <!-- Sudah Kadaluarsa -->
                <div>
                    <h4 style="color: #dc3545; margin-bottom: 15px;">❌ Sudah Kadaluarsa</h4>
                    <p><strong>Total: <?php echo mysqli_num_rows($sudah_kadaluarsa); ?> obat</strong></p>
                    <?php if (mysqli_num_rows($sudah_kadaluarsa) > 0): ?>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <?php while ($obat = mysqli_fetch_assoc($sudah_kadaluarsa)): ?>
                        <div style="background: #f8d7da; padding: 8px; margin-bottom: 5px; border-radius: 5px; border-left: 3px solid #dc3545;">
                            <strong><?php echo $obat['nm_obat']; ?></strong><br>
                            <small>Kadaluarsa: <?php echo format_date($obat['tgl_kadaluarsa']); ?></small>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p style="color: #27ae60; font-style: italic;">✅ Tidak ada obat kadaluarsa</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Laporan Dokter -->
        <div class="card">
            <h2>👨‍⚕️ Laporan Aktivitas Dokter</h2>
            <?php
            $aktivitas_dokter = mysqli_query($conn, "SELECT d.nm_dokter, jk.Nmbidang_keahlian, 
                                                     COUNT(rm.id) as total_pasien,
                                                     COUNT(CASE WHEN DATE(rm.tgl_periksa) = CURDATE() THEN 1 END) as pasien_hari_ini
                                                     FROM dokter d 
                                                     LEFT JOIN jenis_keahlian jk ON d.Kode_Keahlian = jk.Kode_keahlian
                                                     LEFT JOIN rekam_medis rm ON d.id_dokter = rm.id_dokter
                                                     WHERE d.status = 'Aktif'
                                                     GROUP BY d.id_dokter, d.nm_dokter, jk.Nmbidang_keahlian
                                                     ORDER BY total_pasien DESC");
            ?>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dokter</th>
                        <th>Spesialisasi</th>
                        <th>Total Pasien (Keseluruhan)</th>
                        <th>Pasien Hari Ini</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($dokter = mysqli_fetch_assoc($aktivitas_dokter)) {
                        $status_aktivitas = $dokter['pasien_hari_ini'] > 0 ? 
                            '<span style="background: #27ae60; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">🟢 Aktif Hari Ini</span>' :
                            '<span style="background: #95a5a6; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;">⚪ Belum Aktif</span>';
                        
                        echo "<tr>
                            <td>".$no++."</td>
                            <td><strong>Dr. ".$dokter['nm_dokter']."</strong></td>
                            <td><span style='background: #3498db; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem;'>".$dokter['Nmbidang_keahlian']."</span></td>
                            <td><strong>".$dokter['total_pasien']."</strong> pasien</td>
                            <td><strong>".$dokter['pasien_hari_ini']."</strong> pasien</td>
                            <td>".$status_aktivitas."</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Laporan Pendapatan -->
        <div class="card">
            <h2>💰 Laporan Pendapatan</h2>
            <?php
            // Pendapatan dari rawat inap yang sudah selesai
            $pendapatan_rawat_inap = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(SUM(total_Inap), 0) FROM detail_inap WHERE Tgl_keluarinap IS NOT NULL"))[0];
            
            // Pendapatan dari resep obat (estimasi berdasarkan struk yang ada)
            $pendapatan_obat = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(SUM(total_bayar), 0) FROM struk_obat"))[0];
            
            // Pendapatan dari tindakan medis (estimasi)
            $pendapatan_tindakan = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(SUM(tm.harga_alatMedik * dtm.Jum_alatmedik), 0) 
                                                                        FROM detail_tindak_medis dtm 
                                                                        LEFT JOIN tindak_medis tm ON dtm.Kode_tindakMedis = tm.kode_tindakMedis"))[0];
            
            $total_pendapatan = $pendapatan_rawat_inap + $pendapatan_obat + $pendapatan_tindakan;
            ?>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 20px; border-radius: 15px; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 10px;">🏥</div>
                    <h3 style="margin: 0;">Rawat Inap</h3>
                    <div style="font-size: 1.5rem; font-weight: bold; margin-top: 10px;"><?php echo format_currency($pendapatan_rawat_inap); ?></div>
                </div>
                
                <div style="background: linear-gradient(135deg, #27ae60, #229954); color: white; padding: 20px; border-radius: 15px; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 10px;">💊</div>
                    <h3 style="margin: 0;">Penjualan Obat</h3>
                    <div style="font-size: 1.5rem; font-weight: bold; margin-top: 10px;"><?php echo format_currency($pendapatan_obat); ?></div>
                </div>
                
                <div style="background: linear-gradient(135deg, #f39c12, #e67e22); color: white; padding: 20px; border-radius: 15px; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 10px;">🔧</div>
                    <h3 style="margin: 0;">Tindakan Medis</h3>
                    <div style="font-size: 1.5rem; font-weight: bold; margin-top: 10px;"><?php echo format_currency($pendapatan_tindakan); ?></div>
                </div>
                
                <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; padding: 20px; border-radius: 15px; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 10px;">💰</div>
                    <h3 style="margin: 0;">Total Pendapatan</h3>
                    <div style="font-size: 1.8rem; font-weight: bold; margin-top: 10px;"><?php echo format_currency($total_pendapatan); ?></div>
                </div>
            </div>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="index.php" class="btn btn-success">🏠 Kembali ke Dashboard</a>
            <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Laporan</button>
        </div>
    </div>
</body>
</html>
