<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Jika belum login, tidak bisa tembus lewat URL langsung
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data teks dan amankan dari SQL Injection
    $nama          = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis         = mysqli_real_escape_string($conn, $_POST['jenis']);
    $ras           = mysqli_real_escape_string($conn, $_POST['ras']);
    $kategori_umur = mysqli_real_escape_string($conn, $_POST['kategori_umur']);
    $umur_detail   = mysqli_real_escape_string($conn, $_POST['umur_detail']);
    $status_sehat  = mysqli_real_escape_string($conn, $_POST['status_sehat']);
    $deskripsi     = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Manajemen Upload File Foto
    $foto_nama = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $foto_size = $_FILES['foto']['size'];
    
    // Pecah nama file untuk mengambil ekstensi (cth: .jpg, .png)
    $ekstensi_valid = ['jpg', 'jpeg', 'png'];
    $ekstensi_foto  = strtolower(pathinfo($foto_nama, PATHINFO_EXTENSION));

    // Validasi apakah yang diupload benar-benar gambar
    if (!in_array($ekstensi_foto, $ekstensi_valid)) {
        echo "<script>alert('Format file harus JPG, JPEG, atau PNG!'); window.history.back();</script>";
        exit;
    }

    // Validasi ukuran gambar (Maksimal 2MB = 2097152 bytes)
    if ($foto_size > 2097152) {
        echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.'); window.history.back();</script>";
        exit;
    }

    // Enkripsi/Ubah nama file gambar secara acak unik agar tidak bentrok di folder
    $foto_baru = uniqid() . '_' . time() . '.' . $ekstensi_foto;
    
    // Tentukan folder penyimpanan gambar (Pastikan kamu membuat folder bernama 'uploads' di direktori project-mu)
    $folder_tujuan = 'uploads/' . $foto_baru;

    if (move_uploaded_file($foto_tmp, $folder_tujuan)) {
        // Query Insert Data ke Database
        $query = "INSERT INTO pets (nama, jenis, ras, kategori_umur, umur_detail, status_sehat, foto, deskripsi) 
                  VALUES ('$nama', '$jenis', '$ras', '$kategori_umur', '$umur_detail', '$status_sehat', '$foto_baru', '$deskripsi')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>
                    alert('Data sahabat bulu berhasil ditambahkan!');
                    window.location.href = 'admin-dashboard.php';
                  </script>";
        } else {
            echo "Gagal menyimpan data ke database: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Gagal mengunggah foto ke server!'); window.history.back();</script>";
    }
}
?>