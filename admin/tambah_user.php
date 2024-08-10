<?php
session_start();

// Periksa pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// File koneksi.php
require_once "../koneksi.php";

// Fungsi untuk mendapatkan semua admin
function getAdmins($koneksi)
{
    $query = "SELECT * FROM admin";
    return mysqli_query($koneksi, $query);
}

// Mendapatkan semua admin
$admins = getAdmins(getKoneksi());

// Proses tambah admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['adminName'];
    $email = $_POST['adminEmail'];
    $password = password_hash($_POST['adminPassword'], PASSWORD_BCRYPT);
    $role = $_POST['adminRole'];

    // Upload foto
    $target_dir = "../assets/foto/";
    $target_file = $target_dir . basename($_FILES["adminPhoto"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Periksa apakah gambar adalah gambar asli atau palsu
    $check = getimagesize($_FILES["adminPhoto"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Periksa apakah file sudah ada
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }

    // Periksa ukuran file
    if ($_FILES["adminPhoto"]["size"] > 500000) {
        $uploadOk = 0;
    }

    // Hanya izinkan format tertentu
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    // Periksa apakah $uploadOk diatur ke 0 oleh kesalahan
    if ($uploadOk == 0) {
        header("Location: atur_pengguna.php?error=1");
        exit();
    } else {
        if (move_uploaded_file($_FILES["adminPhoto"]["tmp_name"], $target_file)) {
            $photo = basename($_FILES["adminPhoto"]["name"]);
            $query = "INSERT INTO admin (Nama, Email, Password, Role, Photo) VALUES ('$nama', '$email', '$password', '$role', '$photo')";
            if (mysqli_query($koneksi, $query)) {
                header("Location: atur_pengguna.php?success=1");
            } else {
                header("Location: atur_pengguna.php?error=1");
            }
        } else {
            header("Location: atur_pengguna.php?error=1");
        }
    }
}
?>