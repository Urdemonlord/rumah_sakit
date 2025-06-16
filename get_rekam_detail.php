<?php 
include 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT rm.*, p.Nm_Pasien as nama_pasien, p.JK, p.Umur, p.Alamat, p.Tlpn,
                   d.nm_dokter as nama_dokter, jk.Nmbidang_keahlian as spesialis
            FROM rekam_medis rm 
            LEFT JOIN pasien p ON rm.id_Pasien = p.Id_Pasien 
            LEFT JOIN dokter d ON rm.id_dokter = d.id_dokter 
            LEFT JOIN jenis_keahlian jk ON d.Kode_Keahlian = jk.Kode_keahlian
            WHERE rm.id = $id";
    
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $jk_full = ($row['JK'] == 'L') ? 'Laki-laki' : 'Perempuan';
        
        echo "<div style='line-height: 1.6;'>";
        echo "<p><strong>📅 Tanggal Periksa:</strong> ".date('d F Y, H:i', strtotime($row['tgl_periksa']))."</p>";
        echo "<p><strong>👤 Pasien:</strong> ".$row['nama_pasien']." ($jk_full, ".$row['Umur']." tahun)</p>";
        echo "<p><strong>📍 Alamat:</strong> ".$row['Alamat']."</p>";
        echo "<p><strong>📞 Telepon:</strong> ".($row['Tlpn'] ?? 'Tidak ada')."</p>";
        echo "<hr>";
        echo "<p><strong>👨‍⚕️ Dokter:</strong> Dr. ".$row['nama_dokter']."</p>";
        echo "<p><strong>🏥 Spesialis:</strong> ".$row['spesialis']."</p>";
        echo "<hr>";
        echo "<p><strong>😷 Keluhan:</strong></p>";
        echo "<p style='background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 5px 0;'>".$row['keluhan']."</p>";
        echo "<p><strong>🔍 Diagnosa:</strong></p>";
        echo "<p style='background: #e3f2fd; padding: 10px; border-radius: 5px; margin: 5px 0;'>".$row['diagnosa']."</p>";
        echo "<hr>";
        echo "<h4>🩺 Pemeriksaan Fisik:</h4>";
        echo "<p><strong>Tinggi Badan:</strong> ".($row['TB'] ? $row['TB'].' cm' : 'Tidak diukur')."</p>";
        echo "<p><strong>Berat Badan:</strong> ".($row['BB'] ? $row['BB'].' kg' : 'Tidak diukur')."</p>";
        echo "<p><strong>Tekanan Darah:</strong> ".($row['Tensi_darah'] ?? 'Tidak diukur')."</p>";
        echo "</div>";
    } else {
        echo "<p>Data tidak ditemukan.</p>";
    }
} else {
    echo "<p>ID tidak valid.</p>";
}
?>
