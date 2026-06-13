<?php
session_start();
include 'koneksi.php';

// Pastikan hanya admin yang bisa mengakses halaman cetak ini
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Ambil data seluruh hewan terupdate
$query = "SELECT * FROM pets ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Data_Hewan_Shelter_<?php echo date('Y-m-d'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #000;
        }
        .header-laporan {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        /* Mengatur setelan halaman ketika dicetak/disave ke PDF */
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
            }
            @page {
                size: A4 landscape; /* Layout landscape agar tabel muat dengan lega */
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid mt-3">
        <div class="no-print alert alert-info d-flex justify-content-between align-items-center mb-4" role="alert">
            <div>
                <strong>Mode Cetak Laporan PDF:</strong> Jendela cetak akan terbuka otomatis. Pilih opsi <strong>"Save as PDF"</strong> pada kolom Destination browser Anda.
            </div>
            <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold">Cetak / Simpan Ulang</button>
        </div>

        <div class="header-laporan text-center">
            <h2 class="fw-bold text-uppercase m-0">Laporan Data Sahabat Bulu</h2>
            <h4 class="text-secondary m-0">Sistem Manajemen Internal Pet Shelter</h4>
            <p class="text-muted small m-0 mt-1">Dicetak otomatis pada: <?php echo date('d F Y, H:i'); ?> WIB oleh Admin: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
        </div>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Foto Hewan</th>
                    <th>Nama Hewan</th>
                    <th>Jenis</th>
                    <th>Ras</th>
                    <th>Kategori Umur</th>
                    <th>Detail Umur</th>
                    <th style="width: 15%;">Status Kesehatan</th>
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
                        <td class="text-center">
                            <img src="uploads/<?php echo $row['foto']; ?>" alt="Foto">
                        </td>
                        <td class="fw-bold"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis']); ?></td>
                        <td><?php echo htmlspecialchars($row['ras']); ?></td>
                        <td><?php echo htmlspecialchars($row['kategori_umur']); ?></td>
                        <td><?php echo htmlspecialchars($row['umur_detail']); ?></td>
                        <td class="text-center fw-semibold">
                            <?php echo htmlspecialchars($row['status_sehat']); ?>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center py-3 text-muted'>Tidak ada data hewan terdaftar.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="row mt-5 pt-4">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p class="m-0 mb-5">Sukabumi, <?php echo date('d F Y'); ?><br>Pet Shelter Administrator,</p>
                <p class="fw-bold text-decoration-underline m-0"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
                <span class="text-muted small">ID Otentikasi: ACC-<?php echo rand(100,999); ?></span>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>