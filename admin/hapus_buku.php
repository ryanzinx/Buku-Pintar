<?php
session_start();

// Periksa pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// File koneksi.php
require_once "../koneksi.php";

// Koneksi ke database
$koneksi = getKoneksi();

// Periksa apakah parameter ISBN telah diterima
if (isset($_GET['isbn'])) {
    $isbn = mysqli_real_escape_string($koneksi, $_GET['isbn']);

    // Ambil informasi buku untuk menghapus file
    $query_select = "SELECT File_Buku, Cover_Buku FROM buku WHERE ISBN = '$isbn'";
    $result_select = mysqli_query($koneksi, $query_select);
    $row = mysqli_fetch_assoc($result_select);

    // Hapus file buku dan cover dari server
    if ($row) {
        $file_buku = "../" . $row['File_Buku'];
        $cover_buku = "../" . $row['Cover_Buku'];

        if (file_exists($file_buku)) {
            unlink($file_buku);
        } else {
            echo '<script>alert("File buku tidak ditemukan!");</script>';
        }

        if (file_exists($cover_buku)) {
            unlink($cover_buku);
        } else {
            echo '<script>alert("File cover tidak ditemukan!");</script>';
        }

        // Query untuk menghapus buku berdasarkan ISBN
        $query = "DELETE FROM buku WHERE ISBN = '$isbn'";
        if (mysqli_query($koneksi, $query)) {
            // Redirect ke halaman manajemen buku dengan pesan sukses
            header("Location: atur_buku.php?status=sukses");
            exit();
        } else {
            echo '<script>alert("Terjadi kesalahan saat menghapus buku dari database!");</script>';
        }
    } else {
        echo '<script>alert("Buku tidak ditemukan!");</script>';
    }
} else {
    echo '<script>alert("ISBN tidak diberikan!");</script>';
}
?>