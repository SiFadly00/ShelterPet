<?php
// 1. Wajib jalankan session_start() untuk mendeteksi session yang sedang aktif
session_start();

// 2. Bersihkan semua variabel session yang terdaftar
$_SESSION = array();

// 3. Hancurkan session secara total dari memori server
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// 4. Munculkan notifikasi sukses lalu tendang kembali ke halaman login
echo "<script>
        alert('Anda telah berhasil keluar dari sistem admin.');
        window.location.href = 'login.php';
      </script>";
exit;
?>