<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Pastikan hanya admin yang login yang bisa menghapus
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Cek apakah ada parameter ID di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    // Amankan ID dari SQL Injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // STAGE 1: Ambil nama file foto lama sebelum datanya dihapus
    $query_foto  = "SELECT foto FROM pets WHERE id = '$id'";
    $result_foto = mysqli_query($conn, $query_foto);

    if (mysqli_num_rows($result_foto) === 1) {
        $row = mysqli_fetch_assoc($result_foto);
        $nama_foto = $row['foto'];

        // STAGE 2: Hapus data hewan dari database
        $query_delete = "DELETE FROM pets WHERE id = '$id'";
        
        if (mysqli_query($conn, $query_delete)) {
            
            // STAGE 3: Jika data di DB berhasil dihapus, hapus juga file fotonya dari folder uploads
            if (file_exists("uploads/" . $nama_foto) && $nama_foto != "") {
                unlink("uploads/" . $nama_foto);
            }

            // Tampilkan pesan sukses dan kembalikan ke dashboard
            echo "<script>
                    alert('Data sahabat bulu berhasil dihapus dari sistem!');
                    window.location.href = 'admin-dashboard.php';
                  </script>";
            exit;
        } else {
            // Jika query delete gagal
            echo "<script>
                    alert('Gagal menghapus data dari database: " . mysqli_error($conn) . "');
                    window.location.href = 'admin-dashboard.php';
                  </script>";
            exit;
        }
    } else {
        // Jika ID tidak ditemukan di database
        echo "<script>
                alert('Data tidak ditemukan atau sudah dihapus sebelumnya.');
                window.location.href = 'admin-dashboard.php';
              </script>";
        exit;
    }

} else {
    // Jika mencoba akses langsung tanpa ID di URL
    header("Location: admin-dashboard.php");
    exit;
}
?>