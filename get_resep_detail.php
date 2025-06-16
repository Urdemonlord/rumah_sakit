<?php 
include 'db.php';

if (isset($_GET['kode'])) {
    $kode_resep = mysqli_real_escape_string($conn, $_GET['kode']);
    
    // Ambil data resep
    $sql_resep = "SELECT ro.*, p.Nm_Pasien as nama_pasien, p.JK, p.Umur, p.Alamat, p.Tlpn
                  FROM resep_obat ro 
                  LEFT JOIN pasien p ON ro.Id_Pasien = p.Id_Pasien 
                  WHERE ro.Kode_Resep = '$kode_resep'";
    
    $result_resep = mysqli_query($conn, $sql_resep);
    
    if ($result_resep && mysqli_num_rows($result_resep) > 0) {
        $resep = mysqli_fetch_assoc($result_resep);
        
        // Ambil detail obat
        $sql_detail = "SELECT do.*, o.nm_obat, o.harga_obat, o.satuan
                       FROM detail_obat do 
                       LEFT JOIN obat o ON do.kode_obat = o.Kode_obat 
                       WHERE do.Kode_Resep = '$kode_resep'";
        
        $result_detail = mysqli_query($conn, $sql_detail);
        
        $jk_full = ($resep['JK'] == 'L') ? 'Laki-laki' : 'Perempuan';
        
        echo "<div style='line-height: 1.8;'>";
        echo "<p><strong>📋 Kode Resep:</strong> ".$resep['Kode_Resep']."</p>";
        echo "<p><strong>📅 Tanggal:</strong> ".date('d F Y, H:i', strtotime($resep['tgl_resep']))."</p>";
        echo "<p><strong>👤 Pasien:</strong> ".$resep['nama_pasien']." ($jk_full, ".$resep['Umur']." tahun)</p>";
        echo "<p><strong>📍 Alamat:</strong> ".$resep['Alamat']."</p>";
        echo "<p><strong>📞 Telepon:</strong> ".($resep['Tlpn'] ?? 'Tidak ada')."</p>";
        echo "<hr>";
        echo "<h4>💊 Daftar Obat:</h4>";
        
        $total_harga = 0;
        if ($result_detail && mysqli_num_rows($result_detail) > 0) {
            echo "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>";
            echo "<tr style='background: #f8f9fa;'>";
            echo "<th style='padding: 8px; border: 1px solid #ddd;'>Obat</th>";
            echo "<th style='padding: 8px; border: 1px solid #ddd;'>Jumlah</th>";
            echo "<th style='padding: 8px; border: 1px solid #ddd;'>Aturan Pakai</th>";
            echo "<th style='padding: 8px; border: 1px solid #ddd;'>Harga</th>";
            echo "</tr>";
            
            while ($detail = mysqli_fetch_assoc($result_detail)) {
                $subtotal = $detail['Juml_obat'] * $detail['harga_obat'];
                $total_harga += $subtotal;
                
                echo "<tr>";
                echo "<td style='padding: 8px; border: 1px solid #ddd;'><strong>".$detail['nm_obat']."</strong></td>";
                echo "<td style='padding: 8px; border: 1px solid #ddd;'>".$detail['Juml_obat']." ".$detail['satuan']."</td>";
                echo "<td style='padding: 8px; border: 1px solid #ddd;'>".$detail['aturan_Pakai']."</td>";
                echo "<td style='padding: 8px; border: 1px solid #ddd;'>Rp ".number_format($subtotal, 0, ',', '.')."</td>";
                echo "</tr>";
            }
            
            echo "<tr style='background: #e3f2fd; font-weight: bold;'>";
            echo "<td colspan='3' style='padding: 8px; border: 1px solid #ddd; text-align: right;'>TOTAL:</td>";
            echo "<td style='padding: 8px; border: 1px solid #ddd;'>Rp ".number_format($total_harga, 0, ',', '.')."</td>";
            echo "</tr>";
            echo "</table>";
        } else {
            echo "<p>Tidak ada detail obat.</p>";
        }
        
        echo "</div>";
    } else {
        echo "<p>Data resep tidak ditemukan.</p>";
    }
} else {
    echo "<p>Kode resep tidak valid.</p>";
}
?>
