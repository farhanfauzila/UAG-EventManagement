<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $event['nama_event'] ?> - Detail Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .img-detail {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .info-card {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .info-card:hover {
            border-color: #0d6efd;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <a href="index.php" class="btn btn-outline-secondary mb-4 border-0">
            <i class="bi bi-arrow-left"></i> Kembali ke Katalog
        </a>
        
        <div class="card border-0 shadow-sm p-4">
            <div class="row">
                <div class="col-md-5 mb-4 mb-md-0">
                    <img src="assets/img/<?= $event['poster_event'] ?>" 
                         class="img-detail" 
                         alt="Poster <?= $event['nama_event'] ?>"
                         onerror="this.src='https://via.placeholder.com/400x600?text=Poster+Tidak+Ada'">
                </div>
                
                <div class="col-md-7">
                    <span class="badge bg-primary mb-2 px-3 py-2"><?= $event['tipe_event'] ?></span>
                    <h1 class="fw-bold mb-4"><?= $event['nama_event'] ?></h1>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center p-3 info-card shadow-sm">
                                <div class="text-primary me-3">
                                    <i class="bi bi-building fs-3"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">PENYELENGGARA</small>
                                    <span class="fw-bold"><?= htmlspecialchars($event['penyelenggara']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center p-3 info-card shadow-sm">
                                <div class="text-danger me-3">
                                    <i class="bi bi-calendar-event fs-3"></i>
                                </div>
                                <div>
                                <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">TANGGAL PELAKSANAAN</small>
                <span class="fw-bold">
                    <?= date('d F Y', strtotime($event['tanggal_mulai'])) ?>
                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-muted mb-1"><i class="bi bi-geo-alt-fill text-danger"></i> <strong>Lokasi:</strong></p>
                        <p class="fs-5 ps-4 text-secondary"><?= $event['lokasi'] ?></p>
                    </div>

                    <div class="row mb-4 bg-white p-3 rounded border">
                        <div class="col-6 border-end">
                            <p class="text-muted mb-1"><strong>Sisa Kuota:</strong></p>
                            <p class="fs-4 fw-bold mb-0 text-primary"><?= $event['kuota'] ?> <small class="fw-normal fs-6">orang</small></p>
                        </div>
                        <div class="col-6 ps-4">
                            <p class="text-muted mb-1"><strong>Harga Tiket:</strong></p>
                            <p class="fs-4 fw-bold mb-0 text-success">Rp <?= number_format($event['harga'], 0, ',', '.') ?></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted mb-1"><strong>Deskripsi:</strong></p>
                        <div class="p-3 bg-light rounded shadow-sm">
                            <p style="text-align: justify; line-height: 1.6; margin-bottom: 0;">
                                <?= nl2br(htmlspecialchars($event['deskripsi'])) ?>
                            </p>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <?php if($event['kuota'] > 0): ?>
                        <div class="d-grid">
                            <a href="index.php?action=proses_daftar&id=<?= $event['id_event'] ?>" 
                               class="btn btn-primary btn-lg py-3 fw-bold shadow"
                               onclick="return confirm('Apakah Anda yakin ingin mendaftar ke event ini?')">
                               <i class="bi bi-rocket-takeoff-fill me-2"></i>Daftar Sekarang
                            </a>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg w-100 py-3" disabled>
                            <i class="bi bi-x-circle me-2"></i>Maaf, Kuota Habis</button>
                    <?php endif; ?>
                    
                    <?php if ($sudahDaftar): ?>
    <div class="alert alert-success d-flex align-items-center">
        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
        <div>Anda sudah terdaftar di event ini. Silakan cek email atau dashboard untuk info selanjutnya.</div>
    </div>
    <button class="btn btn-secondary btn-lg w-100 py-3" disabled>Sudah Terdaftar</button>
<?php else: ?>
    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>