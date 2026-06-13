<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Pet Shelter</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* Palet Warna Estetik (Sama dengan Dashboard & Form) */
        :root {
            --warm-beige: #fdfbf7;
            --mint-green: #4a7c59;
            --mint-light: #e8f0ec;
            --salmon-peach: #ff9f80;
            --salmon-dark: #e67e5f;
            --dark-slate: #2d3748;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--warm-beige);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: var(--dark-slate);
            padding: 20px;
        }

        /* Desain Card Login */
        .login-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border: 1px solid #f1eedb;
            border-radius: 24px;
        }

        /* Identitas Branding Bulat */
        .brand-icon-box {
            background-color: var(--dark-slate);
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(45, 55, 72, 0.15);
        }

        /* Kustomisasi Form Kontrol & Input */
        .form-control {
            border-radius: 12px;
            border-color: #f1eedb;
            padding: 10px 15px;
            font-size: 0.95rem;
            background-color: #fdfdfb;
        }

        .form-control:focus {
            border-color: var(--mint-green);
            box-shadow: 0 0 0 0.25rem rgba(74, 124, 89, 0.15);
            background-color: white;
        }

        .input-group-text {
            border-color: #f1eedb;
            background-color: #fdfdfb;
            color: #a0aec0;
            border-radius: 12px 0 0 12px;
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Link Kustom */
        .link-custom {
            color: #718096;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .link-custom:hover {
            color: var(--mint-green);
        }

        /* Tombol Utama Hijau Mint */
        .btn-mint {
            background-color: var(--mint-green);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 11px;
            transition: all 0.3s ease;
        }

        .btn-mint:hover {
            background-color: #3b6347;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(74, 124, 89, 0.2);
        }

        .btn-mint:active {
            transform: translateY(0);
        }
    </style>
</head>

<body>

    <div class="card login-card shadow-sm p-3 p-md-4">
        <div class="card-body">

            <!-- Logo & Judul -->
            <div class="text-center mb-4">
                <div class="brand-icon-box mb-3" style="width: 65px; height: 65px;">
                    <i class="bi bi-shield-lock-fill text-warning fs-3"></i>
                </div>
                <h4 class="fw-bold text-dark m-0">Admin Pet Shelter</h4>
                <p class="text-muted small mt-1 mb-0">Panel Akses Manajemen Internal Shelter</p>
            </div>

            <!-- Form Login -->
            <form action="proses-login.php" method="POST">

                <div class="mb-3">
                    <label for="username" class="form-label text-secondary small fw-bold">Username atau Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username admin" value="admin" style="border-radius: 0 12px 12px 0;" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label text-secondary small fw-bold m-0">Password</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" value="password123" style="border-radius: 0 12px 12px 0;" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-mint w-100 fw-bold mb-3 shadow-sm d-flex align-items-center justify-content-center">
                    <span>Masuk ke Dashboard</span>
                    <i class="bi bi-arrow-right-short fs-4 ms-1"></i>
                </button>

                <div class="text-center mt-4">
                    <a href="index.php" class="text-decoration-none small link-custom">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Utama
                    </a>
                </div>
            </form>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>