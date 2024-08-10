<?php
session_start();

// File koneksi.php
require_once "../koneksi.php";

// Ambil data dari form login
$email = $_POST['email'];
$password = $_POST['password'];

// Koneksi ke database
$koneksi = getKoneksi();

// Query untuk memeriksa kecocokan email dan password
$query = "SELECT * FROM admin WHERE Email='$email'";
$result = mysqli_query($koneksi, $query);

// Periksa apakah hasil query mengembalikan baris pengguna
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);

    // Verifikasi password
    if (password_verify($password, $row['Password'])) {
        // Jika cocok, simpan informasi pengguna ke dalam sesi
        $_SESSION['user_id'] = $row['UserID'];
        $_SESSION['nama'] = $row['Nama'];
        $_SESSION['role'] = $row['Role'];

        // Redirect ke halaman admin
        header("Location: dashboard.php");
        exit();
    }
}

// Jika tidak, kirimkan kembali ke halaman login dengan pesan error
header("Location: index.php?error=1");
exit();
?>