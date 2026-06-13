<?php
session_start();
// Proteksi halaman: jika belum login, kembalikan ke login.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tambah Data Hewan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* Palet Warna Estetik */
        :root {
            --warm-beige: #fdfbf7;
            --mint-green: #4a7c59;
            --mint-light: #e8f0ec;
            --salmon-peach: #ff9f80;
            --salmon-dark: #e67e5f;
            --dark-slate: #2d3748;
        }

        body {
            background-color: var(--warm-beige);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: var(--dark-slate);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Admin Style */
        .navbar-admin {
            background-color: var(--dark-slate) !important;
            border-bottom: 3px solid var(--mint-green);
        }

        /* Card Form Style */
        .form-card {
            background: white;
            border: 1px solid #f1eedb;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(45, 55, 72, 0.03) !important;
        }

        /* Input Style Customization */
        .form-label {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--dark-slate);
            margin-bottom: 6px;
        }

        .input-group-text {
            background-color: #faf8f2;
            border-color: #f1eedb;
            color: #a0aec0;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border-color: #f1eedb;
            padding: 11px 16px;
            font-size: 0.95rem;
            background-color: #fdfdfb;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--mint-green);
            box-shadow: 0 0 0 0.25rem rgba(74, 124, 89, 0.12);
            background-color: white;
        }

        /* Custom Input Group Corner Fixes */
        .input-group > .form-control, .input-group > .form-select {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        /* Live Image Preview Container */
        .preview-container {
            width: 100%;
            max-width: 180px;
            height: 180px;
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #faf8f2;
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
        }

        .preview-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Button Style */
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
            border: 1px solid #e2e8f0;
            color: #718096;
            font-weight: 600;
            background-color: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-cancel:hover {
            background-color: #f7fafc;
            color: #4a5568;
            border-color: #cbd5e0;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-admin shadow-sm sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="bi bi-shield-lock-fill text-warning me-2"></i>Admin Pet Shelter
            </a>
            <div class="ms-auto">
                
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11">
                
                <div class="mb-4 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
                    <div>
                        <h3 class="fw-bold text-dark m-0">Tambah Data Sahabat Bulu</h3>
                        <p class="text-muted small m-0">Masukkan informasi detail hewan yang baru masuk ke database internal shelter</p>
                    </div>
                    <a href="admin-dashboard.php" class="btn btn-outline-cancel btn-sm rounded-pill px-3 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Dasbor
                    </a>
                </div>

                <div class="card form-card border-0 p-4 p-md-5">
                    <form action="proses-tambah.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Hewan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
                                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Rocky, Miko, Bella" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jenis Hewan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-gitlab"></i></span>
                                    <select name="jenis" class="form-select" required>
                                        <option value="" disabled selected>Pilih Jenis</option>
                                        <option value="Kucing">Kucing 🐱</option>
                                        <option value="Anjing">Anjing 🐶</option>
                                        <option value="Lainnya">Lainnya (Burung / Kelinci) 🐰</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ras / Breed</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-patch-info-fill"></i></span>
                                    <input type="text" name="ras" class="form-control" placeholder="Contoh: Persia, Golden Retriever" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kategori Umur</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar2-heart-fill"></i></span>
                                    <select name="kategori_umur" class="form-select" required>
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        <option value="Junior">Junior (Kitten / Puppy &lt; 1 Tahun)</option>
                                        <option value="Dewasa">Dewasa (1 - 5 Tahun)</option>
                                        <option value="Senior">Senior (&gt; 5 Tahun)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Detail Umur Spesifik</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hourglass-split"></i></span>
                                    <input type="text" name="umur_detail" class="form-control" placeholder="Contoh: 8 Bulan, 2 Tahun">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status Kesehatan / Shelter</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-heart-pulse-fill"></i></span>
                                    <select name="status_sehat" class="form-select" required>
                                        <option value="Sehat & Steril" selected>Sehat & Steril ✅</option>
                                        <option value="Masa Karantina">Masa Karantina 💛</option>
                                        <option value="Sudah Diadopsi">Sudah Diadopsi 🏥</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Foto Profil Sahabat Bulu</label>
                                <div class="d-flex flex-column flex-sm-row gap-4 align-items-start align-items-sm-center">
                                    <div class="preview-container border shadow-sm" id="imagePreviewContainer">
                                        <div class="text-center text-muted p-2 id-placeholder">
                                            <i class="bi bi-image fs-1 d-block mb-1 text-black-50"></i>
                                            <span style="font-size: 0.75rem;">Belum ada foto</span>
                                        </div>
                                        <img src="" id="outputPreview" class="d-none" alt="Pratinjau Foto">
                                    </div>
                                    <div class="flex-grow-1 w-100">
                                        <input type="file" name="foto" id="fotoInput" class="form-control mb-2" accept="image/*" required>
                                        <div class="form-text text-muted small">
                                            <i class="bi bi-info-circle me-1"></i> Format berkas yang didukung: <strong>JPG, JPEG, atau PNG</strong> (Maksimal dimensi direkomendasikan rasio persegi/1:1, Max file size 2MB).
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Profil / Deskripsi Cerita Hewan</label>
                                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tuliskan latar belakang penyelamatan, kepribadian hewan, atau kebiasaan uniknya..." required></textarea>
                            </div>

                            <div class="col-12 my-2">
                                <hr style="border-color: #f1eedb; opacity: 0.7;">
                            </div>

                            <div class="col-12 d-flex gap-2 justify-content-end">
                                <button type="reset" id="btnReset" class="btn btn-outline-cancel rounded-pill px-4 py-2.5">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Form
                                </button>
                                <button type="submit" class="btn btn-mint rounded-pill px-5 py-2.5 shadow-sm fw-semibold">
                                    <i class="bi bi-cloud-arrow-up-fill me-2"></i>Simpan Data Hewan
                                </button>
                            </div>

                        </div>

                    </form>
                </div> </div>
        </div>
    </div>

    <footer class="text-center text-muted py-4 border-top bg-white mt-auto">
        <p class="mb-0 small">&copy; 2026 Pet Shelter Project. Panel Manajemen CRUD Berbasis Bootstrap 5.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const fotoInput = document.getElementById('fotoInput');
        const outputPreview = document.getElementById('outputPreview');
        const placeholderText = document.querySelector('.id-placeholder');
        const btnReset = document.getElementById('btnReset');

        // Menampilkan gambar saat file diunggah
        fotoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    outputPreview.src = e.target.result;
                    outputPreview.classList.remove('d-none');
                    placeholderText.classList.add('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Menghilangkan gambar kembali ke placeholder asal saat tombol reset ditekan
        btnReset.addEventListener('click', function() {
            outputPreview.src = '';
            outputPreview.classList.add('d-none');
            placeholderText.classList.remove('d-none');
        });
    </script>
</body>
</html>