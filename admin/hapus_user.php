<?php
session_start();

// File koneksi.php
require_once "../koneksi.php";

// Periksa form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminID = $_POST['adminID'];

    // Koneksi ke database
    $koneksi = getKoneksi();

    // Mencegah SQL Injection
    $adminID = mysqli_real_escape_string($koneksi, $adminID);

    // Query untuk mendapatkan foto admin
    $queryGetPhoto = "SELECT Photo FROM admin WHERE UserID='$adminID'";
    $result = mysqli_query($koneksi, $queryGetPhoto);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $photoPath = "../assets/foto/" . $row['Photo'];

        // Hapus foto dari direktori
        if (file_exists($photoPath)) {
            unlink($photoPath);
        }
    }

    // Query untuk menghapus admin
    $queryDeleteAdmin = "DELETE FROM admin WHERE UserID='$adminID'";

    if (mysqli_query($koneksi, $queryDeleteAdmin)) {
        header("Location: atur_pengguna.php?success=1");
    } else {
        header("Location: atur_pengguna.php?error=1");
    }
}
?>