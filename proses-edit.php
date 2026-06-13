<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data form dan amankan dari SQL Injection
    $id            = mysqli_real_escape_string($conn, $_POST['id']);
    $nama          = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis         = mysqli_real_escape_string($conn, $_POST['jenis']);
    $ras           = mysqli_real_escape_string($conn, $_POST['ras']);
    $kategori_umur = mysqli_real_escape_string($conn, $_POST['kategori_umur']);
    $umur_detail   = mysqli_real_escape_string($conn, $_POST['umur_detail']);
    $status_sehat  = mysqli_real_escape_string($conn, $_POST['status_sehat']);
    $deskripsi     = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $foto_lama     = $_POST['foto_lama'];

    // Cek apakah user mengunggah foto baru
    if (isset($_FILES['foto']['name']) && $_FILES['foto']['name'] != "") {
        $filename   = $_FILES['foto']['name'];
        $file_tmp   = $_FILES['foto']['tmp_name'];
        
        // Buat nama unik untuk foto baru mencegah bentrok file
        $file_ext   = pathinfo($filename, PATHINFO_EXTENSION);
        $foto_baru  = time() . "_" . rand(100, 999) . "." . $file_ext;
        $target_dir = "uploads/" . $foto_baru;

        // Pindahkan file baru ke folder uploads
        if (move_uploaded_file($file_tmp, $target_dir)) {
            // Hapus file foto lama dari folder uploads secara fisik
            if (file_exists("uploads/" . $foto_lama) && $foto_lama != "") {
                unlink("uploads/" . $foto_lama);
            }
            // Gunakan nama foto baru untuk query update
            $foto_final = $foto_baru;
        } else {
            echo "<script>
                    alert('Gagal mengunggah foto baru.');
                    window.history.back();
                  </script>";
            exit;
        }
    } else {
        // Jika tidak upload foto baru, tetap pakai foto yang lama
        $foto_final = $foto_lama;
    }

    // Jalankan query update data ke database
    $query = "UPDATE pets SET 
                nama          = '$nama', 
                jenis         = '$jenis', 
                ras           = '$ras', 
                kategori_umur = '$kategori_umur', 
                umur_detail   = '$umur_detail', 
                status_sehat  = '$status_sehat', 
                deskripsi     = '$deskripsi', 
                foto          = '$foto_final' 
              WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location.href = 'admin-dashboard.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui data: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: admin-dashboard.php");
}
?>