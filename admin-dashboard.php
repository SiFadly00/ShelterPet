<?php
session_start();
include 'koneksi.php';

// Proteksi halaman dashboard
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Ambil data dari database untuk ditampilkan di tabel
$query = "SELECT * FROM pets ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pet Shelter</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --warm-beige: #fdfbf7;
            --mint-green: #4a7c59;
            --mint-light: #e8f0ec;
            --salmon-peach: #ff9f80;
            --dark-slate: #2d3748;
        }

        body {
            background-color: var(--warm-beige);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: var(--dark-slate);
        }

        /* Navbar Custom */
        .navbar-admin {
            background-color: var(--dark-slate) !important;
            border-bottom: 3px solid var(--mint-green);
        }

        /* Card & Table Custom */
        .datatable-card {
            border: 1px solid #f1eedb;
            border-radius: 20px;
            background-color: white;
        }

        .table thead {
            background-color: #faf8f2;
        }

        /* Badges Custom untuk Status Kesehatan/Adopsi */
        .badge-status {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-siap {
            background-color: var(--mint-light);
            color: var(--mint-green);
        }

        .badge-karantina {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-diadopsi {
            background-color: #e2e8f0;
            color: #4a5568;
        }

        /* Tombol Utama Hijau Mint */
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

        /* Modifikasi Elemen DataTables agar Selaras dengan Tema */
        .page-item.active .page-link {
            background-color: var(--mint-green) !important;
            border-color: var(--mint-green) !important;
            color: white !important;
        }

        .page-link {
            color: var(--mint-green);
        }

        .form-control-sm:focus,
        .form-select-sm:focus {
            border-color: var(--mint-green) !important;
            box-shadow: 0 0 0 0.2rem rgba(74, 124, 89, 0.15) !important;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-admin shadow-sm sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-shield-lock-fill text-warning navigation-icon me-2"></i>Admin Pet Shelter
            </a>
            <div class="d-flex align-items-center">
                <span class="navbar-text text-white-50 me-3 small">
                    <i class="bi bi-person-circle me-1"></i>Halo, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Log Out</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold text-dark m-0">Kelola Data Hewan</h2>
                <p class="text-muted small m-0">Halaman kontrol utama untuk manajemen manipulasi data (CRUD) internal shelter</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="cetak-pdf.php" target="_blank" class="btn btn-danger rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center fw-semibold">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i>Ekspor PDF
                </a>
                <a href="kelola-adopsi.php" class="btn btn-outline-dark rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center fw-semibold">
                    <i class="bi bi-file-earmark-text me-2"></i>Surat Perjanjian Masuk
                </a>
                <a href="tambah-hewan.php" class="btn btn-mint rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center">
                    <i class="bi bi-plus-circle-fill me-2"></i>Tambah Hewan Baru
                </a>
            </div>
        </div>

        <div class="card datatable-card shadow-sm">
            <div class="card-body p-4">

                <div class="table-responsive">
                    <table id="petTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="text-secondary" style="font-size: 0.9rem;">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 10%;">Foto</th>
                                <th>Nama Hewan</th>
                                <th>Jenis & Ras</th>
                                <th>Umur</th>
                                <th>Status</th>
                                <th style="width: 12%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.95rem;">
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $status = $row['status_sehat'];

                                // Klasifikasi class badge dinamis
                                $badge_class = "badge-siap";
                                if ($status == "Masa Karantina") {
                                    $badge_class = "badge-karantina";
                                } elseif ($status == "Sudah Diadopsi") {
                                    $badge_class = "badge-diadopsi";
                                }
                            ?>
                                <tr>
                                    <td class="fw-bold text-muted"><?php echo $no++; ?></td>
                                    <td>
                                        <img src="uploads/<?php echo $row['foto']; ?>" class="rounded-3 shadow-sm border" style="width: 50px; height: 50px; object-fit: cover;" alt="<?php echo htmlspecialchars($row['nama']); ?>">
                                    </td>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td>
                                        <span class="badge bg-secondary small me-1"><?php echo htmlspecialchars($row['jenis']); ?></span>
                                        <span class="text-muted small"><?php echo htmlspecialchars($row['ras']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['umur_detail']); ?></td>
                                    <td>
                                        <span class="badge-status <?php echo $badge_class; ?>">
                                            <?php echo htmlspecialchars($status); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm shadow-sm" role="group">
                                            <a href="edit-hewan.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-secondary py-2 px-2.5" title="Edit">
                                                <i class="bi bi-pencil-square text-primary"></i>
                                            </a>
                                            <a href="proses-hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-secondary py-2 px-2.5" title="Hapus" onclick="return confirm('Yakin ingin menghapus data sahabat bulu bernama <?php echo $row['nama']; ?>?')">
                                                <i class="bi bi-trash3-fill text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#petTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Maaf, data sahabat bulu tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari Hewan:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "pageLength": 10,
                "ordering": true,
                "columnDefs": [{
                        "orderable": false,
                        "targets": [1, 6]
                    }
                ]
            });
        });
    </script>
</body>

</html>