<?php
// 1. Koneksi ke database
include 'koneksi.php';

// 2. Fitur Hapus Data (Opsional, untuk melengkapi manajemen data admin)
if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus') {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query_hapus = "DELETE FROM adopsi WHERE id = '$id_hapus'";
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>
                alert('Data permohonan adopsi berhasil dihapus!');
                window.location.href = 'kelola-adopsi.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($conn) . "');</script>";
    }
}

// 3. Ambil data permohonan adopsi terbaru (di-JOIN dengan tabel pets)
$query = "SELECT adopsi.*, pets.nama AS nama_hewan, pets.jenis AS jenis_hewan, pets.ras AS ras_hewan
          FROM adopsi 
          LEFT JOIN pets ON adopsi.pet_id = pets.id 
          ORDER BY adopsi.tanggal_submit DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Permohonan Adopsi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { 
            background-color: #f8fafc; 
        }
        .main-card {
            background: #ffffff; 
            border-radius: 16px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: none;
        }
        .img-signature {
            max-height: 60px;
            border: 1px dashed #cbd5e1;
            background-color: #f8fafc;
            padding: 4px;
            border-radius: 6px;
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php" class="text-decoration-none text-secondary">Dashboard</a></li>
                        <li class="breadcrumb-item active fw-bold text-success" aria-current="page">Kelola Adopsi</li>
                    </ol>
                </nav>
                <h3 class="fw-bold text-dark m-0">Dokumen Perjanjian Adopsi</h3>
            </div>
            
            <div class="d-flex gap-2">
                <a href="cetak-adopsi.php" target="_blank" class="btn btn-sm btn-danger rounded-3 px-3 d-inline-flex align-items-center">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Semua PDF
                </a>
                <a href="admin-dashboard.php" class="btn btn-sm btn-outline-secondary rounded-3 px-3 d-inline-flex align-items-center">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
            </div>
        </div>

        <div class="card main-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                <span class="text-secondary small">Total Permohonan: <strong><?php echo mysqli_num_rows($result); ?></strong> berkas</span>
                <button class="btn btn-light btn-sm text-secondary" onclick="window.location.reload();">
                    <i class="bi bi-arrow-clockwise"></i> Refresh Data
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="25%">Data Adopter</th>
                            <th width="25%">Hewan Pilihan</th>
                            <th width="15%">Waktu Submit</th>
                            <th width="15%" class="text-center">Tanda Tangan</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($result) > 0) {
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) { 
                        ?>
                            <tr>
                                <td class="text-center text-secondary"><?php echo $no++; ?></td>
                                <td>
                                    <div class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($row['nama_pemohon']); ?></div>
                                    <a href="https://wa.me/<?php echo $row['whatsapp']; ?>" target="_blank" class="badge bg-success-subtle text-success text-decoration-none border border-success-subtle py-1.5 px-2">
                                        <i class="bi bi-whatsapp me-1"></i><?php echo htmlspecialchars($row['whatsapp']); ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark mb-0"><?php echo htmlspecialchars($row['nama_hewan'] ?? 'Hewan Tidak Ditemukan'); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['jenis_hewan'] ?? '-'); ?> (<?php echo htmlspecialchars($row['ras_hewan'] ?? '-'); ?>)</small>
                                </td>
                                <td class="text-secondary small">
                                    <i class="bi bi-calendar3 me-1"></i><?php echo date('d/m/Y', strtotime($row['tanggal_submit'])); ?><br>
                                    <i class="bi bi-clock me-1"></i><?php echo date('H:i', strtotime($row['tanggal_submit'])); ?> WIB
                                </td>
                                <td class="text-center">
                                    <?php if(!empty($row['tanda_tangan'])): ?>
                                        <img src="<?php echo $row['tanda_tangan']; ?>" class="img-signature" alt="Signature">
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">Kosong</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="cetak-detail-adopsi.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-outline-primary btn-sm px-2.5" title="Cetak Surat Perjanjian Individu">
                                            <i class="bi bi-printer-fill"></i>
                                        </a>
                                        <a href="kelola-adopsi.php?aksi=hapus&id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm px-2.5" onclick="return confirm('Apakah Anda yakin ingin menghapus arsip surat perjanjian dari <?php echo htmlspecialchars($row['nama_pemohon']); ?>?')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-file-earmark-x display-4 text-warning mb-3 d-block"></i>
                                    <span class="fw-medium">Belum ada dokumen perjanjian adopsi yang masuk dari customer.</span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>