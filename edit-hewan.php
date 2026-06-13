<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Cek apakah ada ID yang dikirim melalui URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin-dashboard.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data lama hewan berdasarkan ID
$query = "SELECT * FROM pets WHERE id = '$id'";
$result = mysqli_query($conn, $query);

// Jika data tidak ditemukan, kembalikan ke dashboard
if (mysqli_num_rows($result) === 0) {
    header("Location: admin-dashboard.php");
    exit;
}

$pet = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Hewan - Admin Pet Shelter</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --warm-beige: #fdfbf7;
            --mint-green: #4a7c59;
            --mint-light: #e8f0ec;
            --dark-slate: #2d3748;
        }

        body {
            background-color: var(--warm-beige);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: var(--dark-slate);
        }

        .navbar-admin {
            background-color: var(--dark-slate) !important;
            border-bottom: 3px solid var(--mint-green);
        }

        .form-card {
            border: 1px solid #f1eedb;
            border-radius: 24px;
            background: white;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border-color: #f1eedb;
            padding: 10px 15px;
            background-color: #fdfdfb;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--mint-green);
            box-shadow: 0 0 0 0.25rem rgba(74, 124, 89, 0.15);
            background-color: white;
        }

        .btn-mint {
            background-color: var(--mint-green);
            color: white;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-mint:hover {
            background-color: #3b6347;
            color: white;
            box-shadow: 0 4px 12px rgba(74, 124, 89, 0.2);
        }

        .btn-outline-cancel {
            border: 1px solid #cbd5e0;
            color: #718096;
            font-weight: 500;
        }

        .btn-outline-cancel:hover {
            background-color: #f7fafc;
            color: #4a5568;
        }

        .current-photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 16px;
            border: 2px solid var(--mint-light);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-admin shadow-sm sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="admin-dashboard.php">
                <i class="bi bi-shield-lock-fill text-warning me-2"></i>Admin Pet Shelter
            </a>
            <div class="d-flex align-items-center">
                <span class="navbar-text text-white-50 small"><i class="bi bi-person-circle me-1"></i>Mode Edit Data</span>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                
                <div class="mb-4">
                    <h2 class="fw-bold text-dark m-0">Edit Data Sahabat Bulu</h2>
                    <p class="text-muted small m-0">Perbarui informasi detail mengenai hewan asuhan shelter</p>
                </div>

                <div class="card form-card shadow-sm p-4 p-md-5">
                    <form action="proses-edit.php" method="POST" enctype="multipart/form-data">
                        
                        <input type="hidden" name="id" value="<?php echo $pet['id']; ?>">
                        <input type="hidden" name="foto_lama" value="<?php echo $pet['foto']; ?>">
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">Nama Hewan</label>
                                <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($pet['nama']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">Jenis Hewan</label>
                                <select name="jenis" class="form-select" required>
                                    <option value="Kucing" <?php echo ($pet['jenis'] == 'Kucing') ? 'selected' : ''; ?>>Kucing 🐱</option>
                                    <option value="Anjing" <?php echo ($pet['jenis'] == 'Anjing') ? 'selected' : ''; ?>>Anjing 🐶</option>
                                    <option value="Lainnya" <?php echo ($pet['jenis'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya 🐰</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">Ras / Breed</label>
                                <input type="text" name="ras" class="form-control" value="<?php echo htmlspecialchars($pet['ras']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">Kategori Umur</label>
                                <select name="kategori_umur" class="form-select" required>
                                    <option value="Junior" <?php echo ($pet['kategori_umur'] == 'Junior') ? 'selected' : ''; ?>>Junior</option>
                                    <option value="Dewasa" <?php echo ($pet['kategori_umur'] == 'Dewasa') ? 'selected' : ''; ?>>Dewasa</option>
                                    <option value="Senior" <?php echo ($pet['kategori_umur'] == 'Senior') ? 'selected' : ''; ?>>Senior</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">Detail Umur Spesifik</label>
                                <input type="text" name="umur_detail" class="form-control" value="<?php echo htmlspecialchars($pet['umur_detail']); ?>" placeholder="Contoh: 8 Bulan">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">Status Kesehatan</label>
                                <select name="status_sehat" class="form-select" required>
                                    <option value="Siap Diadopsi" <?php echo ($pet['status_sehat'] == 'Siap Diadopsi') ? 'selected' : ''; ?>>Siap Diadopsi ✅</option>
                                    <option value="Masa Karantina" <?php echo ($pet['status_sehat'] == 'Masa Karantina') ? 'selected' : ''; ?>>Masa Karantina 💛</option>
                                    <option value="Sudah Diadopsi" <?php echo ($pet['status_sehat'] == 'Sudah Diadopsi') ? 'selected' : ''; ?>>Sudah Diadopsi 🏥</option>
                                </select>
                            </div>

                            <div class="col-12 d-flex align-items-center gap-3 bg-light p-3 rounded-4 border">
                                <img src="uploads/<?php echo $pet['foto']; ?>" class="current-photo shadow-sm" alt="Foto saat ini">
                                <div>
                                    <label class="form-label fw-bold text-secondary small m-0">Ganti Foto Hewan</label>
                                    <p class="text-muted small mb-2">Biarkan kosong jika tidak ingin mengubah foto saat ini.</p>
                                    <input type="file" name="foto" class="form-control form-control-sm" accept="image/*">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-secondary small">Profil / Deskripsi Cerita Hewan</label>
                                <textarea name="deskripsi" class="form-control" rows="4" required><?php echo htmlspecialchars($pet['deskripsi']); ?></textarea>
                            </div>

                            <div class="col-12 d-flex gap-3 justify-content-end mt-4">
                                <a href="admin-dashboard.php" class="btn btn-outline-cancel rounded-pill px-4 d-flex align-items-center">Batal</a>
                                <button type="submit" class="btn btn-mint rounded-pill px-5">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>

</html>