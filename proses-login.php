<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query  = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // FUNGSI KHUSUS UNTUK MEMBONGKAR DAN MEMVERIFIKASI HASH DATABASE
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username']  = $row['username'];
            
            header("Location: admin-dashboard.php");
            exit;
        }
    }
    
    echo "<script>
            alert('Username atau Password salah!');
            window.location.href = 'login.php';
          </script>";
}
?>