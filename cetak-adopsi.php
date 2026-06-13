<?php
session_start();
include 'koneksi.php';

// Proteksi halaman cetak
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Query JOIN sesuai dengan schema database kamu
$query = "SELECT adopsi.*, pets.nama AS nama_hewan, pets.jenis AS jenis_hewan 
          FROM adopsi 
          JOIN pets ON adopsi.pet_id = pets.id 
          ORDER BY adopsi.id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Adopsi_<?php echo date('Y-m-d'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #fff;
            color: #000;
        }
        .header-laporan {
            border-bottom: 3px double #2d3748;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        .img-signature {
            max-height: 50px;
            object-fit: contain;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
            }
            @page {
                size: A4 landscape;
                margin: 1.2cm;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid mt-3">
        <div class="no-print alert alert-info d-flex justify-content-between align-items-center mb-4 border-0 shadow-sm" style="background-color: #e8f0ec; color: #4a7c59;">
            <div>
                <strong>Format Cetak PDF:</strong> Gunakan opsi <strong>"Save as PDF"</strong> dengan orientasi <strong>Landscape</strong> agar tabel tidak terpotong.
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-sm text-white fw-bold" style="background-color: #4a7c59;">
                    <i class="bi bi-printer"></i> Cetak Ulang
                </button>
                <a href="admin-dashboard.php" class="btn btn-sm btn-outline-secondary fw-bold">Kembali ke Dasbor</a>
            </div>
        </div>

        <div class="header-laporan text-center">
            <h2 class="fw-bold text-uppercase m-0" style="color: #2d3748;">Laporan Data Pengajuan Adopsi</h2>
            <h5 class="text-muted m-0">Sistem Informasi Manajemen Berkas Pet Shelter</h5>
            <p class="text-secondary small m-0 mt-1">Dicetak pada: <?php echo date('d-m-Y H:i'); ?> WIB</p>
        </div>

        <table class="table table-bordered table-striped align-middle" style="font-size: 0.88rem;">
            <thead class="table-dark text-center" style="background-color: #2d3748;">
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 14%;">Tanggal Submit</th>
                    <th>Nama Pemohon</th>
                    <th>No. WhatsApp</th>
                    <th>Hewan yang Diadopsi</th>
                    <th style="width: 20%;">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td class="text-center fw-bold"><?php echo $no++; ?></td>
                        <td class="text-center"><?php echo date('d-m-Y H:i', strtotime($row['tanggal_submit'])); ?></td>
                        <td class="fw-semibold"><?php echo htmlspecialchars($row['nama_pemohon']); ?></td>
                        <td><?php echo htmlspecialchars($row['whatsapp']); ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['nama_hewan']); ?></strong> 
                            <span class="text-muted small">(<?php echo htmlspecialchars($row['jenis_hewan']); ?>)</span>
                        </td>
                        <td class="text-center bg-white">
                            <?php if(!empty($row['tanda_tangan'])): ?>
                                <img src="<?php echo $row['tanda_tangan']; ?>" class="img-signature" alt="Tanda Tangan">
                            <?php else: ?>
                                <span class="text-muted small fst-italic">Tidak ada</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Belum ada data pengajuan adopsi di database.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="row mt-5 pt-3">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p class="m-0 mb-5">Mengetahui,<br>Petugas Administrasi Shelter</p>
                <p class="fw-bold text-decoration-underline m-0">Management Staff</p>
                <span class="text-muted d-block" style="font-size: 0.75rem;">Verifikasi Otomatis Sistem</span>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { window.print(); }, 600);
        });
    </script>
</body>
</html>