# Pet Shelter Sukabumi - Web-Based Animal Adoption System

Aplikasi berbasis web ini dirancang untuk mempermudah proses manajemen penampungan hewan (*pet shelter*) serta pengelolaan dokumen pengajuan adopsi secara resmi dan terdigitalisasi. Aplikasi ini menjembatani calon pengadopsi (*adopter*) dengan pihak shelter melalui proses administrasi yang efisien, transparan, dan aman.

## Deskripsi Project
Proyek ini dikembangkan menggunakan **Native PHP** dengan dukungan database relasional **MySQL**. Fokus utama dari modul yang berjalan saat ini adalah memigrasikan alur penandatanganan berkas kesepakatan adopsi konvensional ke bentuk digital. 

Calon adopter dapat memilih hewan, mengisi identitas, dan membutuhkan **tanda tangan digital langsung pada layar perangkat** mereka. Sistem kemudian akan merekam data tersebut dan menyediakan fitur cetak otomatis dokumen hukum berupa *Surat Perjanjian Adopsi Hewan* resmi yang siap diarsipkan atau dicetak fisik.

## Fitur Utama yang Tersedia
1. **Sistem Autentikasi Admin Aman**: Login admin dilindungi dengan enkripsi password berbasis algoritma **Bcrypt** (`password_hash` & `password_verify()`), menjamin keamanan akun dari serangan siber dasar.
2. **Digital Signature Pad**: Memanfaatkan HTML5 Canvas dan JavaScript untuk menangkap goresan tangan adopter secara *real-time*, lalu dikonversi menjadi format gambar **Base64** untuk disimpan ke dalam database.
3. **Generator & Cetak Dokumen Otomatis**: Halaman cetak yang dioptimalkan menggunakan Bootstrap 5 dan CSS `@media print` untuk menghasilkan *layout* surat perjanjian formal, lengkap dengan Kop Resmi Shelter dan penempatan tanda tangan digital yang presisi.
4. **Relasi Database Terstruktur**: Menggunakan mekanisme *Foreign Key* yang menghubungkan data pengajuan adopsi secara langsung dengan entitas spesifik di tabel hewan (*pets*).

## Struktur Database (`pet_shelter`)
Aplikasi ini didukung oleh tiga tabel utama yang saling berelasi:
* **`users`**: Menyimpan kredensial akun petugas/admin (`id`, `username`, `password` *[Hash Varchar 255]*).
* **`pets`**: Menyimpan data karakteristik hewan penampungan (`id`, `nama`, `jenis`, `ras`, `kategori_umur`, `umur_detail`, `status_sehat`, `foto`, `deskripsi`, `created_at`).
* **`adopsi`**: Menyimpan data transaksi legalitas adopsi (`id`, `pet_id` *[FK]*, `nama_pemohon`, `whatsapp`, `tanda_tangan` *[Longtext Base64]*, `tanggal_submit`).

## Progres Pengembangan (Project Progress)

### **Tahap 1: Inisialisasi & Basis Data (Selesai)**
- [x] Perancangan skema database relasional `pet_shelter`.
- [x] Pembuatan struktur tabel `users`, `pets`, dan `adopsi`.
- [x] Konfigurasi file gerbang koneksi database (`koneksi.php`).

### **Tahap 2: Sisi Publik / Form Adopter (Selesai)**
- [x] Pembuatan form pengisian Surat Perjanjian Adopsi Resmi.
- [x] Integrasi komponen Tanda Tangan Digital berbasis HTML5 Canvas.
- [x] Validasi pengiriman form (mencegah submit jika belum ada tanda tangan).
- [x] Penanganan transmisi data gambar Base64 dari frontend ke query SQL.
- [x] Fitur pengalihan (*redirection*) kembali ke Beranda Publik (`index.php`) setelah data berhasil disimpan.

### **Tahap 3: Fitur Dokumen & Otomatisasi Cetak (Selesai)**
- [x] Pembuatan berkas template cetak surat (`cetak-detail-adopsi.php`).
- [x] Implementasi query *LEFT JOIN* untuk menggabungkan relasi data pemohon dengan data spesifikasi hewan.
- [x] Perbaikan bug tata letak CSS pada komponen Kop Surat (`text-align: center`).
- [x] Injeksi fungsi pemicu cetak otomatis jendela browser lewat JavaScript (`window.print()`).

### **Tahap 4: Panel Admin & Dashboard CRUD (Selesai)**
- [x] Perbaikan celah keamanan sistem verifikasi login menggunakan validasi `password_verify()`.
- [x] Pembuatan halaman rekapitulasi data pengajuan adopsi yang masuk untuk divalidasi oleh petugas.

## 🚀 Teknologi yang Digunakan
* **Backend:** PHP (Native)
* **Frontend:** HTML5, CSS3, JavaScript (Vanilla), Bootstrap 5, Bootstrap Icons
* **Database:** MySQL (diperjalankan via Laragon)
* **Keamanan:** Bcrypt Password Hashing
