<?php
// 1. Sambungkan ke database
include 'koneksi.php';

// 2. Tangkap data filter pencarian jika ada yang dikirimkan user
$search        = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$jenis         = isset($_GET['jenis']) ? mysqli_real_escape_string($conn, $_GET['jenis']) : '';
$kategori_umur = isset($_GET['kategori_umur']) ? mysqli_real_escape_string($conn, $_GET['kategori_umur']) : '';

// 3. Bangun query dasar SQL
$query = "SELECT * FROM pets WHERE 1=1";

// Jika input kata kunci diisi
if (!empty($search)) {
    $query .= " AND (nama LIKE '%$search%' OR ras LIKE '%$search%')";
}
// Jika filter jenis hewan dipilih
if (!empty($jenis) && $jenis != 'Semua Jenis Hewan') {
    $query .= " AND jenis = '$jenis'";
}
// Jika filter kategori umur dipilih
if (!empty($kategori_umur) && $kategori_umur != 'Semua Kategori Umur') {
    $query .= " AND kategori_umur = '$kategori_umur'";
}

// Urutkan dari yang paling baru ditambahkan
$query .= " ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Shelter - Temukan Sahabat Barumu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
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
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        .nav-link {
            color: var(--dark-slate) !important;
            font-weight: 500;
        }
        .nav-link.active, .nav-link:hover {
            color: var(--mint-green) !important;
        }

        .hero-box {
            background-color: var(--mint-light);
            border-radius: 30px;
            padding: 60px 40px;
            margin-top: 20px;
        }
        .btn-salmon {
            background-color: var(--salmon-peach);
            color: white;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-salmon:hover {
            background-color: var(--salmon-dark);
            color: white;
            transform: translateY(-2px);
        }
        .btn-mint-outline {
            border: 2px solid var(--mint-green);
            color: var(--mint-green);
            font-weight: 600;
        }
        .btn-mint-outline:hover {
            background-color: var(--mint-green);
            color: white;
        }

        .pet-card-modern {
            background: white;
            border: 1px solid #f1eedb;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .pet-card-modern:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(74, 124, 89, 0.1) !important;
        }
        .badge-mint {
            background-color: var(--mint-light);
            color: var(--mint-green);
        }
        .badge-salmon {
            background-color: #ffebe5;
            color: var(--salmon-dark);
        }

        .carousel-item img, .carousel-item video {
            height: 450px;
            object-fit: cover;
            border-radius: 24px;
        }
        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            border-radius: 16px;
            padding: 20px;
            bottom: 30px;
            z-index: 10;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="index.php">
                <i class="bi bi-heart-pulse-fill text-danger me-2"></i>Pet Shelter
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#galeri">Galeri Shelter</a></li>
                    <li class="nav-item"><a class="nav-link" href="#katalog">Cari Hewan</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="login.php" class="btn btn-mint-outline btn-sm rounded-pill px-4">
                            <i class="bi bi-lock-fill me-1"></i> Admin Area
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <header class="hero-box my-4 shadow-sm">
            <div class="row align-items-center g-4">
                <div class="col-lg-6 px-lg-5 text-center text-lg-start">
                    <span class="badge badge-mint mb-3 px-3 py-2 rounded-pill fw-bold">🐾 Adopsi, Jangan Beli</span>
                    <h1 class="display-5 fw-bold text-dark mb-3" style="line-height: 1.2;">Bawa Kebahagiaan Baru ke Rumah Anda</h1>
                    <p class="text-secondary mb-4 fs-5">Ratusan ekor anjing dan kucing telantar di shelter kami siap menjadi anggota baru keluarga Anda yang setia.</p>
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                        <a href="#katalog" class="btn btn-salmon btn-lg rounded-pill px-4">Lihat Katalog</a>
                        <a href="#galeri" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Lihat Shelter</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=600&h=400&fit=crop" class="img-fluid rounded-4 shadow" alt="Hewan Shelter">
                </div>
            </div>
        </header>

        <section id="galeri" class="py-5">
            <div class="mb-4">
                <h3 class="fw-bold text-dark m-0">Suasana di Shelter Kami</h3>
                <p class="text-muted small">Kombinasi foto fasilitas dan cuplikan video aktivitas harian hewan di shelter</p>
            </div>

            <div id="shelterCarousel" class="carousel slide shadow rounded-4 overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#shelterCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#shelterCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#shelterCarousel" data-bs-slide-to="2"></button>
                </div>
                
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="4000">
                        <img src="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?q=80&w=1200&h=500&fit=crop" class="d-block w-100" alt="Area Bermain">
                        <div class="carousel-caption d-none d-md-block">
                            <h5 class="fw-bold">Taman Bermain Terbuka</h5>
                            <p class="small mb-0">Tempat interaksi bebas bagi para anjing dan kucing agar terhindar dari stres.</p>
                        </div>
                    </div>

                    <div class="carousel-item" data-bs-interval="6000">
                        <video class="d-block w-100" autoplay muted loop playsinline>
                            <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                            Browser kamu tidak mendukung pemutaran video HTML5.
                        </video>
                        <div class="carousel-caption d-none d-md-block">
                            <h5 class="fw-bold"><i class="bi bi-play-btn-fill text-danger me-2"></i>Tur Documentation Shelter</h5>
                            <p class="small mb-0">Cuplikan video singkat suasana kebersihan kamar dan pemeliharaan rutin tim medis.</p>
                        </div>
                    </div>

                    <div class="carousel-item" data-bs-interval="4000">
                        <img src="https://images.unsplash.com/photo-1601758228041-f3b2795255f1?q=80&w=1200&h=500&fit=crop" class="d-block w-100" alt="Waktu Makan">
                        <div class="carousel-caption d-none d-md-block">
                            <h5 class="fw-bold">Pemberian Nutrisi Teratur</h5>
                            <p class="small mb-0">Setiap jadwal makan disesuaikan dengan kebutuhan gizi masing-masing ras hewan.</p>
                        </div>
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#shelterCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#shelterCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

        <section id="pencarian" class="pt-4 pb-2">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 24px; background: white;">
                <div class="row align-items-center mb-3">
                    <div class="col-12">
                        <h5 class="fw-bold m-0 text-dark">
                            <i class="bi bi-filter-circle-fill me-2" style="color: var(--mint-green);"></i>Filter Pencarian Hewan
                        </h5>
                        <p class="text-muted small m-0">Gunakan filter di bawah ini untuk mempercepat pencarian sahabat barumu</p>
                    </div>
                </div>
                
                <form action="index.php#katalog" method="GET">
                    <div class="row g-3">
                        
                        <div class="col-lg-4 col-md-12">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 py-2.5" style="border-radius: 20px 0 0 20px; border-color: #f1eedb;">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 py-2.5" placeholder="Cari nama atau ras (cth: Luna, Persia)..." value="<?php echo htmlspecialchars($search); ?>" style="border-radius: 0 20px 20px 0; border-color: #f1eedb; box-shadow: none; font-size: 0.95rem;">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <select name="jenis" class="form-select py-2.5" style="border-radius: 20px; border-color: #f1eedb; box-shadow: none; font-size: 0.95rem; background-color: var(--warm-beige);">
                                <option value="Semua Jenis Hewan">Semua Jenis Hewan</option>
                                <option value="Kucing" <?php echo ($jenis == 'Kucing') ? 'selected' : ''; ?>>Kucing 🐱</option>
                                <option value="Anjing" <?php echo ($jenis == 'Anjing') ? 'selected' : ''; ?>>Anjing 🐶</option>
                                <option value="Lainnya" <?php echo ($jenis == 'Lainnya') ? 'selected' : ''; ?>>Burung / Kelinci 🐰</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <select name="kategori_umur" class="form-select py-2.5" style="border-radius: 20px; border-color: #f1eedb; box-shadow: none; font-size: 0.95rem; background-color: var(--warm-beige);">
                                <option value="Semua Kategori Umur">Semua Kategori Umur</option>
                                <option value="Junior" <?php echo ($kategori_umur == 'Junior') ? 'selected' : ''; ?>>Junior (&lt; 1 Tahun)</option>
                                <option value="Dewasa" <?php echo ($kategori_umur == 'Dewasa') ? 'selected' : ''; ?>>Dewasa (1 - 5 Tahun)</option>
                                <option value="Senior" <?php echo ($kategori_umur == 'Senior') ? 'selected' : ''; ?>>Senior (&gt; 5 Tahun)</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-12">
                            <button type="submit" class="btn btn-salmon w-100 py-2.5 rounded-pill fw-bold" style="font-size: 0.95rem;">
                                <i class="bi bi-sliders me-1"></i> Terapkan
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </section>

        <section id="katalog" class="py-5">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-dark m-0">Sahabat yang Menunggumu</h3>
                    <p class="text-muted small">Pilih mereka yang paling cocok dengan energi rumahmu</p>
                </div>
                <?php if(!empty($search) || (!empty($jenis) && $jenis != 'Semua Jenis Hewan') || (!empty($kategori_umur) && $kategori_umur != 'Semua Kategori Umur')): ?>
                    <a href="index.php#katalog" class="btn btn-sm btn-outline-secondary rounded-pill">Reset Filter</a>
                <?php endif; ?>
            </div>

            <div class="row g-4">
                <?php 
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) { 
                        $badge_type_class = ($row['jenis'] == 'Kucing') ? 'badge-mint' : 'badge-salmon';
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm pet-card-modern p-3">
                        <img src="uploads/<?php echo $row['foto']; ?>" class="img-fluid rounded-4 mb-3" style="height: 230px; object-fit: cover; width: 100%;" alt="<?php echo htmlspecialchars($row['nama']); ?>">
                        <div class="d-flex flex-column h-100 justify-content-between">
                            <div>
                                <span class="badge <?php echo $badge_type_class; ?> mb-2"><?php echo htmlspecialchars($row['jenis']); ?> • <?php echo htmlspecialchars($row['ras']); ?></span>
                                <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($row['nama']); ?></h5>
                                <p class="text-muted small mb-3">Detail Umur: <?php echo htmlspecialchars($row['umur_detail']); ?></p>
                                <p class="text-secondary small mb-3 text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo htmlspecialchars($row['deskripsi']); ?>
                                </p>
                            </div>
                            <button class="btn btn-salmon btn-sm w-100 rounded-pill py-2" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo $row['id']; ?>">
                                Pelajari Detail
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalDetail<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow-lg" style="background-color: var(--warm-beige);">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="fw-bold text-dark m-0">Profil Lengkap Anabul</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <img src="uploads/<?php echo $row['foto']; ?>" class="img-fluid rounded-4 mb-3 shadow-sm" style="max-height: 300px; width: 100%; object-fit: cover;" alt="<?php echo htmlspecialchars($row['nama']); ?>">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="fw-bold text-dark m-0"><?php echo htmlspecialchars($row['nama']); ?></h4>
                                    <span class="badge bg-success rounded-pill px-3 py-1.5"><?php echo htmlspecialchars($row['status_sehat']); ?></span>
                                </div>
                                <div class="mb-3">
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($row['jenis']); ?></span>
                                    <span class="badge bg-dark">Ras: <?php echo htmlspecialchars($row['ras']); ?></span>
                                    <span class="badge bg-info text-dark">Kategori: <?php echo htmlspecialchars($row['kategori_umur']); ?></span>
                                </div>
                                <p class="text-secondary small mb-4" style="line-height: 1.6; text-align: justify;">
                                    <?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?>
                                </p>
                                <a href="adoption-agreement.php?id=<?php echo $row['id']; ?>" class="btn btn-dark w-100 py-2.5 fw-bold rounded-pill">
                                    <i class="bi bi-pencil-square me-2 text-salmon"></i>Adopsi & Isi Surat Perjanjian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    } 
                } else { 
                ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-emoji-frown text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 fw-bold text-secondary">Aww, Sahabat Bulu Tidak Ditemukan</h5>
                    <p class="text-muted small">Coba cari dengan kata kunci lain atau reset filter kamu.</p>
                </div>
                <?php } ?>
            </div>
        </section>

    </div>

    <footer class="text-center text-muted py-4 mt-5 border-top">
        <p class="mb-0 small">&copy; 2026 Pet Shelter Project. Desain HTML Pemrograman Web.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>