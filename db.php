<?php
// Database Configuration
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_rumah_sakit";

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("❌ Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");

// Function to sanitize input
function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Function to format currency
function format_currency($number) {
    return "Rp " . number_format($number, 0, ',', '.');
}

// Function to format date
function format_date($date) {
    return date('d/m/Y', strtotime($date));
}

// Function to format datetime
function format_datetime($datetime) {
    return date('d/m/Y H:i', strtotime($datetime));
}
?>