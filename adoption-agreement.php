<?php
// 1. Koneksi ke Database
include 'koneksi.php';

// 2. Ambil ID Hewan dari URL untuk dicantumkan di dalam surat resmi
$pet_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

$nama_hewan = "Hewan Pilihan";
$detail_hewan = "";

if ($pet_id > 0) {
    $query_pet = "SELECT nama, jenis, ras FROM pets WHERE id = '$pet_id'";
    $result_pet = mysqli_query($conn, $query_pet);
    if (mysqli_num_rows($result_pet) === 1) {
        $pet = mysqli_fetch_assoc($result_pet);
        $nama_hewan = $pet['nama'];
        $detail_hewan = "(" . $pet['jenis'] . " • " . $pet['ras'] . ")";
    }
}

// 3. Proses Pengiriman Form (Saat di-klik Kirim)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pemohon  = mysqli_real_escape_string($conn, $_POST['nama_pemohon']);
    $whatsapp      = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $tanda_tangan  = mysqli_real_escape_string($conn, $_POST['tanda_tangan_base64']);
    $id_hewan_form = mysqli_real_escape_string($conn, $_POST['pet_id']);

    // Validasi sederhana agar data tidak kosong
    if (!empty($nama_pemohon) && !empty($whatsapp) && !empty($tanda_tangan)) {
        
        $query_insert = "INSERT INTO adopsi (pet_id, nama_pemohon, whatsapp, tanda_tangan) 
                         VALUES ('$id_hewan_form', '$nama_pemohon', '$whatsapp', '$tanda_tangan')";

        if (mysqli_query($conn, $query_insert)) {
            // 💡 PERBAIKAN DI SINI: Alihan diubah dari admin-dashboard.php ke index.php
            echo "<script>
                    alert('Dokumen Hukum Adopsi Berhasil Ditandatangani dan Dikirim ke Admin!');
                    window.location.href = 'index.php';
                  </script>";
            exit;
        } else {
            echo "<script>alert('Gagal menyimpan dokumen: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Harap isi data dengan lengkap dan goreskan tanda tangan Anda!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perjanjian Adopsi - Pet Shelter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .signature-container {
            position: relative;
            background-color: #ffffff;
        }
        #signature-pad {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            cursor: crosshair;
            background-color: #fafafa;
            touch-action: none;
        }
        #signature-pad:active {
            border-color: #198754;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="mb-4">
                    <a href="index.php" class="text-decoration-none text-secondary small">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda Publik
                    </a>
                </div>

                <form id="form-adopsi" method="POST" action="">
                    <input type="hidden" name="pet_id" value="<?php echo $pet_id; ?>">
                    <input type="hidden" name="tanda_tangan_base64" id="tanda_tangan_base64">

                    <div class="card shadow border-0 rounded-4 mb-4">
                        <div class="card-body p-5">
                            
                            <div class="text-center mb-4 border-bottom pb-4">
                                <h3 class="fw-bold text-dark mb-1">SURAT PERJANJIAN ADOPSI HEWAN</h3>
                                <p class="text-muted small mb-0">Nomor: SPA/SHELTER-PET/<?php echo date('Y'); ?>/001</p>
                            </div>

                            <div class="bg-light p-4 rounded-3 mb-4 border">
                                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-vcard-fill me-2 text-success"></i>Identitas Adopter</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Nama Lengkap Pemohon</label>
                                        <input type="text" name="nama_pemohon" class="form-control" placeholder="Contoh: Mochamad Fadly" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Nomor WhatsApp Aktif</label>
                                        <input type="tel" name="whatsapp" class="form-control" placeholder="Contoh: 081234567xx" required>
                                    </div>
                                </div>
                            </div>

                            <div class="agreement-text text-secondary mb-5" style="line-height: 1.8; text-align: justify;">
                                <p>Yang bertanda tangan di bawah ini, selanjutnya disebut sebagai <strong>Adopter (Pemohon Adopsi)</strong>, menyatakan sepakat dan berjanji dengan sungguh-sungguh kepada pihak <strong>Pet Shelter</strong> untuk mematuhi poin-poin berikut terkait hewan yang diadopsi bernama <strong class="text-dark"><?php echo htmlspecialchars($nama_hewan) . " " . htmlspecialchars($detail_hewan); ?></strong>:</p>
                                
                                <ol class="ps-3 mb-4">
                                    <li class="mb-2"><strong>Perawatan Layak:</strong> Berkomitmen memberikan makanan bergizi, air bersih, tempat tinggal yang aman, dan kasih sayang yang cukup bagi hewan tersebut.</li>
                                    <li class="mb-2"><strong>Kesehatan & Vaksinasi:</strong> Bersedia membawa hewan ke klinik hewan atau dokter hewan berwenang jika hewan sakit dan melengkapi jadwal vaksinasi tahunan.</li>
                                    <li class="mb-2"><strong>Larangan Penelantaran:</strong> Tidak akan menelantarkan, menyiksa, menjual, atau memindahtangankan hewan ini kepada pihak ketiga tanpa persetujuan tertulis dari pihak Pet Shelter.</li>
                                    <li class="mb-2"><strong>Pengawasan Shelter:</strong> Bersedia memberikan pembaruan kabar (update) berkala mengenai kondisi hewan jika sewaktu-waktu dihubungi oleh tim verifikasi shelter.</li>
                                </ol>

                                <p>Demikian surat perjanjian ini dibuat atas kesadaran penuh dan tanpa paksaan dari pihak manapun, untuk digunakan sebagaimana mestinya.</p>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6 offset-md-6 text-center">
                                    <p class="mb-2 text-dark fw-bold">Tanda Tangan Digital Adopter:</p>
                                    
                                    <div class="signature-container p-2 border rounded-3 bg-light">
                                        <canvas id="signature-pad" width="340" height="180"></canvas>
                                        
                                        <div class="d-flex justify-content-between mt-2 px-1">
                                            <span class="text-muted small my-auto"><i class="bi bi-info-circle me-1"></i>Goreskan jari/mouse</span>
                                            <button type="button" id="clear-btn" class="btn btn-outline-danger btn-sm px-2 py-1">
                                                <i class="bi bi-eraser-fill me-1"></i>Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top pt-4 mt-5 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success btn-lg px-4 fw-bold rounded-3" id="btn-submit">
                                    <i class="bi bi-check-circle me-2"></i>Kirim Perjanjian Resmi
                                </button>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const canvas = document.getElementById('signature-pad');
        const ctx = canvas.getContext('2d'); 
        const clearButton = document.getElementById('clear-btn');
        const formAdopsi = document.getElementById('form-adopsi');
        const inputBase64 = document.getElementById('tanda_tangan_base64');
        
        let isDrawing = false;
        let hasSigned = false;

        ctx.strokeStyle = "#1e293b"; 
        ctx.lineWidth = 3;           
        ctx.lineCap = "round";       
        ctx.lineJoin = "round";      

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return { x: clientX - rect.left, y: clientY - rect.top };
        }

        function startDrawing(e) {
            isDrawing = true;
            hasSigned = true;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            e.preventDefault();
        }

        function draw(e) {
            if (!isDrawing) return;
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            e.preventDefault();
        }

        function stopDrawing() { isDrawing = false; }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing);
        canvas.addEventListener('touchmove', draw);
        window.addEventListener('touchend', stopDrawing);

        clearButton.addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasSigned = false;
        });

        formAdopsi.addEventListener('submit', function(e) {
            if (!hasSigned) {
                e.preventDefault(); 
                alert('Silakan bubuhkan tanda tangan Anda terlebih dahulu pada kotak yang disediakan!');
                return false;
            }
            
            const dataURL = canvas.toDataURL('image/png');
            inputBase64.value = dataURL; 
        });
    </script>
</body>
</html>