<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - UAG Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 0; }
        .navbar { margin-bottom: 0 !important; }
        
        /* Carousel Styling */
        .carousel-item img {
            height: 80vh;
            object-fit: cover;
            filter: brightness(45%);
        }
        .carousel-caption {
            top: 50%;
            transform: translateY(-50%);
            bottom: initial;
        }
        .btn-kuning {
            background-color: #ffc107;
            color: #000;
            border: none;
            padding: 12px 35px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 5px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-kuning:hover { background-color: #e0a800; color: #000; transform: scale(1.05); }

        /* Section Intro */
        .intro-section { background: white; padding: 80px 0; }
        .feature-box {
            padding: 30px;
            border-radius: 15px;
            transition: 0.3s;
            background: #f8f9fa;
        }
        .feature-box:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        
        /* Event Card */
        .card-event {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
        }
        .card-event:hover { transform: scale(1.02); }
    </style>
</head>
<body>

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="public/images/carousel/event1.jpeg" class="d-block w-100" alt="Banner 1">
                <div class="carousel-caption text-center">
                    <h1 class="display-3 fw-bold">Wujudkan Potensimu!</h1>
                    <p class="fs-4 mb-4">Platform manajemen event mahasiswa UAG untuk masa depan lebih gemilang.</p>
                    <a href="index.php?action=katalog" class="btn-kuning">Cari Event Sekarang</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="public/images/carousel/event2.jpeg" class="d-block w-100" alt="Banner 2">
                <div class="carousel-caption text-center">
                    <h1 class="display-3 fw-bold">Belajar & Berjejaring</h1>
                    <p class="fs-4 mb-4">Ikuti seminar dan workshop terbaik langsung dari kampusmu.</p>
                    <a href="index.php?action=katalog" class="btn-kuning">Explore Lebih Jauh</a>
                </div>
            </div>
        </div>
    </div>

    <section class="intro-section">
        <div class="container text-center">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-3">Satu Pintu Menuju Pengalaman Tak Terlupakan</h2>
                    <p class="lead text-muted">UAG Event Management hadir untuk memudahkan mahasiswa mengakses ribuan peluang pengembangan diri. Jangan hanya jadi penonton, jadilah bagian dari perubahan!</p>
                </div>
            </div>
            
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="feature-box h-100">
                        <h4 class="fw-bold text-dark">Kemudahan Akses</h4>
                        <p class="text-muted">Cari, pilih, dan daftar event hanya dengan beberapa klik. Tidak ada lagi proses birokrasi yang rumit.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box h-100">
                        <h4 class="fw-bold text-dark">Pembayaran Praktis</h4>
                        <p class="text-muted">Sistem pendaftaran berbayar yang transparan. Unggah bukti bayarmu dan status akan langsung diproses.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box h-100">
                        <h4 class="fw-bold text-dark">Update Real-Time</h4>
                        <p class="text-muted">Dapatkan informasi kuota sisa secara langsung. Amankan kursimu sebelum kehabisan!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold m-0">EVENT TERBARU</h2>
                <a href="index.php?action=katalog" class="text-warning fw-bold text-decoration-none">Lihat Semua →</a>
            </div>

            <div class="row g-4">
                <?php if (!empty($events_terbaru)): ?>
                    <?php foreach ($events_terbaru as $ev): ?>
                        <div class="col-md-4">
                            <div class="card card-event shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-primary fw-bold"><?= strtoupper($ev['kategori'] ?? 'EVENT') ?></small>
                                        <small class="text-muted"><?= date('d M Y', strtotime($ev['tanggal_mulai'])) ?></small>
                                    </div>
                                    <h5 class="card-title fw-bold text-dark"><?= $ev['nama_event'] ?></h5>
                                    <p class="card-text text-muted small mb-4">
                                        <?= substr(strip_tags($ev['deskripsi']), 0, 100) ?>...
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="fw-bold text-success">
                                            <?= $ev['harga'] == 0 ? 'GRATIS' : 'Rp '.number_format($ev['harga'], 0, ',', '.') ?>
                                        </span>
                                        <a href="index.php?action=detail&id=<?= $ev['id_event'] ?>" class="btn btn-sm btn-dark px-3">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Belum ada event terbaru saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 text-center">
        <p class="m-0">&copy; 2026 UAG Event Management. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>