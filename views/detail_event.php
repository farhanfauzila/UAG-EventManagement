<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $event['nama_event'] ?> - UAG Event</title>
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
            padding-top: 100px;
            font-family: 'Inter', sans-serif;
        }

        .btn-back {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-back:hover {
            transform: translateX(-5px);
            color: #0d6efd;
        }

        .img-detail {
            width: 100%;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            transition: 0.3s;
        }

        .event-card-main {
            background: white;
            border-radius: 30px;
            border: none;
            overflow: hidden;
        }

        .info-pill {
            background: var(--base);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid #eee;
            height: 100%;
            transition: 0.3s;
        }

        .info-pill:hover {
            border-color: var(--accent);
            background: white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .price-banner {
            background: var(--primary);
            color: white;
            padding: 30px;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-register-uag {
            background: var(--accent);
            color: var(--primary);
            border: none;
            padding: 18px;
            font-weight: 800;
            border-radius: 15px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.4s;
        }

        .btn-register-uag:hover:not(:disabled) {
            background: white;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .section-title {
            font-size: 0.8rem;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
            display: block;
        }

        .sticky-sidebar {
            position: sticky;
            top: 120px;
        }
    </style>
</head>

<body>

    <div class="container mb-5">
        <div class="mb-4">
            <a href="index.php?action=katalog" class="btn-back">
                <i class="bi bi-arrow-left-circle-fill me-2"></i> Kembali ke Katalog
            </a>
        </div>

        <div class="row g-5">
            <div class="col-lg-7">
                <img src="assets/img/<?= $event['poster_event'] ?>" class="img-detail mb-5"
                    alt="Poster <?= $event['nama_event'] ?>"
                    onerror="this.src='https://via.placeholder.com/800x1000?text=Poster+Not+Found'">

                <div class="description-box px-2">
                    <span class="section-title">Tentang Event</span>
                    <h2 class="fw-800 text-dark mb-4"><?= htmlspecialchars($event['nama_event']) ?></h2>
                    <div class="text-secondary" style="line-height: 1.8; font-size: 1.05rem; text-align: justify;">
                        <?= nl2br(htmlspecialchars($event['deskripsi'] ?? '')) ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="sticky-sidebar">
                    <div class="event-card-main shadow-sm p-4">
                        <span class="badge mb-3"
                            style="background: var(--accent); color: var(--primary); font-weight: 700; padding: 8px 15px;">
                            <i class="bi bi-tag-fill me-1"></i> <?= $event['tipe_event'] ?>
                        </span>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="info-pill">
                                    <i class="bi bi-calendar3 d-block fs-4 mb-2 text-primary"></i>
                                    <small class="text-muted d-block">Tanggal</small>
                                    <span
                                        class="fw-bold small"><?= date('d M Y', strtotime($event['tanggal_mulai'])) ?></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-pill">
                                    <i class="bi bi-geo-alt d-block fs-4 mb-2 text-danger"></i>
                                    <small class="text-muted d-block">Lokasi</small>
                                    <span class="fw-bold small"><?= $event['lokasi'] ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-pill d-flex align-items-center">
                                    <i class="bi bi-people fs-4 me-3 text-info"></i>
                                    <div>
                                        <small class="text-muted d-block">Sisa Kuota</small>
                                        <span class="fw-bold"><?= $event['kuota'] ?> Kursi Tersedia</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="price-banner mb-4 shadow">
                            <div>
                                <small class="opacity-75 d-block">Harga Tiket</small>
                                <h3 class="fw-bold mb-0">
                                    <?= $event['harga'] == 0 ? 'GRATIS' : 'Rp ' . number_format($event['harga'], 0, ',', '.') ?>
                                </h3>
                            </div>
                            <i class="bi bi-ticket-perforated fs-1 opacity-25"></i>
                        </div>

                        <?php
                        $today = date('Y-m-d');
                        $is_expired = ($event['tanggal_selesai'] < $today);
                        ?>

                        <div class="d-grid gap-3">
                            <?php if ($is_expired || $event['status_event'] === 'completed'): ?>
                                <div class="alert alert-secondary border-0 rounded-4 d-flex align-items-center mb-0">
                                    <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                                    <small class="fw-bold">Event ini telah berakhir. Pendaftaran otomatis ditutup.</small>
                                </div>
                                <button class="btn btn-secondary py-3 fw-bold rounded-4" disabled>EVENT SELESAI</button>

                            <?php elseif ($sudahDaftar): ?>
                                <div class="alert alert-success border-0 rounded-4 d-flex align-items-center mb-0">
                                    <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                                    <small class="fw-bold">Anda sudah terdaftar untuk event ini.</small>
                                </div>
                                <button class="btn btn-secondary py-3 fw-bold rounded-4" disabled>SUDAH TERDAFTAR</button>

                            <?php elseif ($event['kuota'] > 0): ?>
                                <a href="index.php?action=proses_daftar&id=<?= $event['id_event'] ?>"
                                    class="btn btn-register-uag shadow-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin mendaftar?')">
                                    DAFTAR SEKARANG <i class="bi bi-arrow-right-short ms-2"></i>
                                </a>
                            <?php else: ?>
                                <button class="btn btn-danger py-3 fw-bold rounded-4 shadow-sm" disabled>
                                    <i class="bi bi-exclamation-octagon me-2"></i>KUOTA HABIS
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="text-center mt-4">
                            <small class="text-muted italic">Butuh bantuan? <a href="#"
                                    class="text-decoration-none fw-bold">Hubungi Admin</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>