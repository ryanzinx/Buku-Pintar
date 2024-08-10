<?php
// Koneksi ke database
$host = 'localhost'; 
$dbname = 'bukupintar'; 
$username = 'root'; 
$password = ''; 

// Membuat koneksi
$koneksi = new mysqli($host, $username, $password, $dbname);

// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

// Fungsi untuk mendapatkan koneksi
function getKoneksi() {
    global $koneksi;
    return $koneksi;
}
?>
