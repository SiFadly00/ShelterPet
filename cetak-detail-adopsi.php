<?php
session_start();
include 'koneksi.php';

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT adopsi.*, pets.nama AS nama_hewan, pets.jenis AS jenis_hewan, pets.ras AS ras_hewan, pets.kategori_umur
          FROM adopsi 
          LEFT JOIN pets ON adopsi.pet_id = pets.id 
          WHERE adopsi.id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if(!$data) { echo "Dokumen tidak ditemukan."; exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat_Perjanjian_<?php echo htmlspecialchars($data['nama_pemohon']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', Times, serif; padding: 2cm; background: #fff; }
        /* Perbaikan pada text-align: center di bawah ini */
        .kop { border-bottom: 4px solid #000; padding-bottom: 10px; margin-bottom: 30px; text-align: center; }
        .isi-surat { line-height: 1.8; text-align: justify; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print alert alert-secondary text-end p-2 mb-4">
        <button onclick="window.print()" class="btn btn-dark btn-sm">Cetak Sekarang</button>
    </div>

    <div class="kop text-center">
        <h3 class="fw-bold m-0">PET SHELTER SUKABUMI</h3>
        <p class="m-0 text-muted">Jalan Raya Universitas Muhammadiyah Sukabumi • Telp: (0266) 123456</p>
    </div>

    <h4 class="text-center fw-bold text-uppercase mb-4">Surat Perjanjian Adopsi Hewan</h4>
    
    <div class="isi-surat mt-4">
        <p>Yang bertanda tangan di bawah ini menerangkan bahwa permohonan adopsi telah disetujui:</p>
        <table class="table table-borderless ms-4">
            <tr><td width="30%">Nama Lengkap Adopter</td><td>: <strong><?php echo htmlspecialchars($data['nama_pemohon']); ?></strong></td></tr>
            <tr><td>No. WhatsApp / Kontak</td><td>: <?php echo htmlspecialchars($data['whatsapp']); ?></td></tr>
            <tr><td>Tanggal Pengajuan</td><td>: <?php echo date('d F Y, H:i', strtotime($data['tanggal_submit'])); ?> WIB</td></tr>
        </table>

        <p class="mt-3">Dengan ini berkomitmen penuh untuk merawat, menjaga kesehatan, serta memberikan kasih sayang secara bertanggung jawab terhadap hewan peliharaan berikut:</p>
        <table class="table table-bordered bg-light text-center">
            <thead>
                <tr><th>Nama Hewan</th><th>Jenis Hewan</th><th>Ras</th><th>Kategori Umur</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong><?php echo htmlspecialchars($data['nama_hewan']); ?></strong></td>
                    <td><?php echo htmlspecialchars($data['jenis_hewan']); ?></td>
                    <td><?php echo htmlspecialchars($data['ras_hewan']); ?></td>
                    <td><?php echo htmlspecialchars($data['kategori_umur']); ?></td>
                </tr>
            </tbody>
        </table>
        <p>Surat perjanjian ini dibuat secara sadar tanpa ada paksaan dari pihak manapun untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="row mt-5 text-center">
        <div class="col-6">
            <p>Pihak Kedua (Adopter)</p>
            <div class="my-3">
                <img src="<?php echo $data['tanda_tangan']; ?>" style="max-height: 80px;" alt="Tanda Tangan">
            </div>
            <p class="fw-bold"><?php echo htmlspecialchars($data['nama_pemohon']); ?></p>
        </div>
        <div class="col-6">
            <p>Sukabumi, <?php echo date('d-m-Y'); ?><br>Pihak Pertama (Shelter)</p>
            <div style="height: 80px;"></div>
            <p class="fw-bold text-decoration-underline">Petugas Shelter</p>
        </div>
    </div>

    <script>window.print();</script>
</body>
</html>