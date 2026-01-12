<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - UAG Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #1d4372;
            --base: #f5f8f7;
            --accent: #d1e8fa;
        }

        body {
            background-color: var(--base);
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .carousel-item {
            height: 100vh;
            min-height: 600px;
        }

        .carousel-item img {
            height: 100vh;
            object-fit: cover;
            filter: brightness(40%) contrast(110%);
        }

        .carousel-caption {
            top: 50%;
            transform: translateY(-50%);
            bottom: auto;
            text-align: left;
            max-width: 700px;
            left: 10%;
        }

        .carousel-caption h1 {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -2px;
            color: var(--accent);
            margin-bottom: 20px;
        }

        .carousel-caption p {
            font-size: 1.2rem;
            opacity: 0.9;
            border-left: 4px solid var(--accent);
            padding-left: 20px;
        }

        .btn-uag {
            background-color: var(--accent);
            color: var(--primary);
            border: none;
            padding: 15px 40px;
            font-weight: 700;
            border-radius: 50px;
            transition: 0.4s;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-uag:hover {
            background-color: white;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .intro-section {
            padding: 100px 0;
            background-color: white;
        }

        .feature-box {
            padding: 40px;
            border-radius: 24px;
            background: var(--base);
            border: 1px solid #eee;
            transition: 0.3s ease;
        }

        .feature-box i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
            display: block;
        }

        .feature-box:hover {
            background: var(--accent);
            transform: translateY(-10px);
        }

        .event-section {
            padding: 100px 0;
            position: relative;
            background: linear-gradient(rgba(55, 100, 150, 0.9), rgba(55, 100, 150, 0.8)),
                url('public/images/latar_event.jpg');
            background-attachment: fixed;
            background-size: cover;
            color: white;
        }

        .card-event {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: 0.4s;
            color: white;
        }

        .card-event:hover {
            background: white;
            color: var(--primary);
        }

        .card-event:hover .text-muted,
        .card-event:hover .text-white-50 {
            color: var(--primary) !important;
            opacity: 0.7;
        }
    </style>
</head>

<body>

    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="public/images/carousel/event1.jpeg" class="d-block w-100" alt="Banner 1">
                <div class="carousel-caption">
                    <h1 class="animate__animated animate__fadeInUp">GROW<br>BEYOND.</h1>
                    <p class="mb-5">Belajar, bertemu, dan bertumbuh bersama event UAG.</p>
                    <a href="index.php?action=katalog" class="btn-uag">Explore Event</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="public/images/carousel/event2.jpeg" class="d-block w-100" alt="Banner 2">
                <div class="carousel-caption">
                    <h1 class="animate__animated animate__fadeInUp">CONNECT<br>& LEARN.</h1>
                    <p class="mb-5">Bangun relasi dan keahlian baru.</p>
                    <a href="index.php?action=katalog" class="btn-uag">Discover Now</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <section class="intro-section">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-lightning-charge"></i>
                        <h4 class="fw-bold">Fast Access</h4>
                        <p class="text-muted">Pendaftaran kilat tanpa ribet.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-wallet2"></i>
                        <h4 class="fw-bold">Easy Payment</h4>
                        <p class="text-muted">Konfirmasi otomatis dan transparan untuk setiap transaksi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="bi bi-shield-check"></i>
                        <h4 class="fw-bold">Verified</h4>
                        <p class="text-muted">Event resmi yang diakui kampus dengan sertifikat digital.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="event-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div>
                    <h6 class="text-uppercase fw-bold" style="color: var(--accent); letter-spacing: 2px;">Terbaru</h6>
                    <h2 class="display-5 fw-bold m-0">EVENT PILIHAN</h2>
                </div>
                <a href="index.php?action=katalog" class="btn btn-outline-light rounded-pill px-4">Lihat Semua</a>
            </div>

            <div class="row g-4">
                <?php if (!empty($events_terbaru)):
                    foreach ($events_terbaru as $ev): ?>
                        <div class="col-md-4">
                            <div class="card card-event h-100 shadow">
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="badge" style="background: var(--accent); color: var(--primary)">
                                            <?= htmlspecialchars($ev['tipe_event'] ?? 'EVENT') ?>
                                        </span>
                                        <small class="text-white-50">
                                            <?= isset($ev['tanggal_mulai']) ? date('d M Y', strtotime($ev['tanggal_mulai'])) : '-' ?>
                                        </small>
                                    </div>

                                    <h4 class="fw-bold mb-3"><?= htmlspecialchars($ev['nama_event'] ?? 'Untitled Event') ?></h4>

                                    <p class="text-white-50 small mb-4">
                                        <?php
                                        $deskripsi_bersih = strip_tags($ev['deskripsi'] ?? '');
                                        echo substr($deskripsi_bersih, 0, 80) . (strlen($deskripsi_bersih) > 80 ? '...' : '');
                                        ?>
                                    </p>

                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <span class="fs-5 fw-bold">
                                            <?php
                                            $harga = (float) ($ev['harga'] ?? 0);
                                            echo $harga == 0 ? 'FREE' : 'Rp ' . number_format($harga, 0, ',', '.');
                                            ?>
                                        </span>
                                        <a href="index.php?action=detail&id=<?= $ev['id_event'] ?>"
                                            class="btn btn-light btn-sm px-3 fw-bold rounded-pill">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Tidak ada event tersedia saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer style="background-color: var(--primary); padding: 30px 0;" class="text-white text-center">
        <p class="m-0 opacity-75">&copy; 2026 UAG Event Management. Designed with Passion.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>