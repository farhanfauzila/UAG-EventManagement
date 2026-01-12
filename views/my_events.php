<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Saya - UAG Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #1d4372;
            --base: #f5f8f7;
            --accent: #d1e8fa;
        }

        body { background-color: var(--base); font-family: 'Inter', sans-serif; padding-top: 100px; }

        .profile-card {
            background: linear-gradient(135deg, var(--primary) 0%, #0a1d36 100%);
            color: white;
            border-radius: 30px;
            padding: 40px;
            margin-bottom: 50px;
            box-shadow: 0 15px 35px rgba(29, 67, 114, 0.2);
        }

        .profile-img { 
            width: 100px; height: 100px; 
            object-fit: cover; border-radius: 25px; 
            border: 4px solid rgba(255,255,255,0.2);
        }

        .event-card { 
            border: none; border-radius: 24px; 
            background: white; transition: all 0.3s ease; 
            overflow: hidden; height: 100%;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .event-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }

        .status-badge { 
            position: absolute; top: 15px; right: 15px; 
            padding: 8px 16px; border-radius: 50px; 
            font-size: 0.7rem; font-weight: 800; 
            text-transform: uppercase; z-index: 10;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .info-box {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 12px;
        }

        .btn-logout-uag {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ff6b6b;
            font-weight: 700; border-radius: 50px;
        }
        .btn-logout-uag:hover { background: #ff6b6b; color: white; }
    </style>
</head>
<body>

    <div class="container mb-5">
        <div class="profile-card">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                    <?php 
                        $avatar_url = "https://ui-avatars.com/api/?name=".urlencode($_SESSION['nama'])."&background=d1e8fa&color=1d4372&size=128&bold=true";
                    ?>
                    <img src="<?= $avatar_url ?>" class="profile-img" alt="Avatar">
                </div>
                <div class="col-md-7 text-center text-md-start">
                    <h5 class="opacity-75 mb-1" style="color: var(--accent);">Selamat Datang,</h5>
                    <h1 class="fw-bold mb-2"><?= $_SESSION['nama'] ?></h1>
                    <p class="mb-0 opacity-75"><i class="bi bi-mortarboard me-2"></i>Mahasiswa UAG</p>
                </div>
                <div class="col-md-3 text-center text-md-end">
                    <a href="index.php?action=logout" class="btn btn-logout-uag px-4 py-2" onclick="return confirm('Yakin ingin keluar?')">
                        <i class="bi bi-box-arrow-right me-2"></i> LOGOUT
                    </a>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark m-0"><i class="bi bi-rocket-takeoff me-2 text-primary"></i>Event Saya</h4>
            <a href="index.php?action=katalog" class="btn btn-primary rounded-pill px-4">Cari Event Lain</a>
        </div>

        <div class="row g-4">
            <?php if (empty($my_registrations)): ?>
                <div class="col-12 text-center py-5">
                    <div class="card border-0 rounded-4 p-5 bg-white shadow-sm">
                        <i class="bi bi-calendar-x display-1 text-muted opacity-25"></i>
                        <h4 class="text-muted mt-3">Kamu belum mendaftar ke event mana pun.</h4>
                        <a href="index.php?action=katalog" class="btn btn-primary mt-3 px-5 py-2 rounded-pill">Explore Event Sekarang</a>
                    </div>
                </div>
            <?php else: foreach ($my_registrations as $reg): 
                $is_pending = ($reg['status_pembayaran'] === 'pending');
                $is_paid = ($reg['status_pembayaran'] === 'paid' || $reg['status_pembayaran'] === 'free');
                $sudah_upload = !empty($reg['bukti_pembayaran']);
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card event-card position-relative">
                        <?php if ($is_pending && $sudah_upload): ?>
                            <span class="status-badge bg-info text-white">Diverifikasi</span>
                        <?php elseif ($is_pending): ?>
                            <span class="status-badge bg-warning text-dark">Pending</span>
                        <?php elseif ($is_paid): ?>
                            <span class="status-badge bg-success text-white">Terdaftar</span>
                        <?php endif; ?>

                        <div class="ratio ratio-4x3">
                            <img src="assets/img/<?= htmlspecialchars($reg['poster_event'] ?? 'default.jpg') ?>" 
                                 class="card-img-top" 
                                 alt="Poster Event"
                                 style="object-fit: cover;"
                                 onerror="this.src='https://via.placeholder.com/400x300?text=Poster+UAG'">
                        </div>
                        
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-3 text-dark text-truncate"><?= htmlspecialchars($reg['nama_event']) ?></h5>
                            
                            <div class="info-box mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-calendar3 me-2 text-primary"></i>
                                    <small class="text-muted me-auto">Pelaksanaan:</small>
                                    <small class="fw-bold"><?= date('d M Y', strtotime($reg['tanggal_mulai'])) ?></small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                                    <small class="text-muted me-auto">Lokasi:</small>
                                    <small class="fw-bold text-truncate" style="max-width: 120px;"><?= $reg['lokasi'] ?></small>
                                </div>
                            </div>
                            
                            <div class="mt-auto pt-2">
                                <?php if ($is_pending && !$sudah_upload): ?>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary fw-bold py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalBayar<?= $reg['id_regist'] ?>">
                                            <i class="bi bi-upload me-2"></i>UPLOAD BUKTI BAYAR
                                        </button>
                                        <a href="index.php?action=cancel_reg&id=<?= $reg['id_regist'] ?>" class="btn btn-sm text-danger text-decoration-none fw-bold" onclick="return confirm('Batalkan pendaftaran?')">Batalkan Pendaftaran</a>
                                    </div>
                                <?php elseif ($is_paid && ($reg['status_event'] ?? '') === 'completed'): ?>
                                    <button class="btn btn-success w-100 fw-bold py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalSertif<?= $reg['id_regist'] ?>">
                                        <i class="bi bi-award me-2"></i>AMBIL SERTIFIKAT
                                    </button>
                                <?php elseif ($is_pending && $sudah_upload): ?>
                                    <button class="btn btn-outline-info w-100 fw-bold py-2 rounded-3" disabled>PROSES VERIFIKASI</button>
                                <?php else: ?>
                                    <button class="btn btn-light w-100 text-muted fw-bold py-2 rounded-3" disabled>MENUNGGU ACARA</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalBayar<?= $reg['id_regist'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content shadow-lg">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fw-bold">Konfirmasi Pembayaran</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="index.php?action=upload_bukti" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id_regist" value="<?= $reg['id_regist'] ?>">
                                <div class="modal-body p-4">
                                    <p class="text-muted small mb-1">Silakan transfer sebesar:</p>
                                    <h3 class="fw-bold text-primary mb-4">Rp <?= number_format($reg['harga'], 0, ',', '.') ?></h3>
                                    <div class="p-3 bg-light rounded-3 border mb-4 text-center">
                                        <small class="fw-bold text-dark d-block mb-1 text-uppercase">Metode Pembayaran:</small>
                                        <p class="mb-0 small">Transfer ke: <strong>BCA 12345678</strong> (UAG Management)</p>
                                    </div>
                                    <label class="form-label fw-bold small">PILIH FILE BUKTI (JPG/PNG)</label>
                                    <input type="file" name="bukti_file" class="form-control rounded-3" required>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3">KIRIM BUKTI</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalSertif<?= $reg['id_regist'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
            <div class="modal-header bg-success text-white border-0 px-4 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-patch-check me-2"></i>E-Sertifikat Tersedia</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-award-fill text-success" style="font-size: 4rem;"></i>
                </div>
                <h5 class="fw-bold">Selamat, <?= htmlspecialchars($_SESSION['nama']) ?>!</h5>
                <p class="text-muted small mb-4">Kamu telah menyelesaikan event <strong><?= htmlspecialchars($reg['nama_event']) ?></strong>. Berikut adalah akses sertifikat digitalmu:</p>
                
                <div class="p-3 bg-light rounded-4 border border-dashed mb-3">
                    <label class="small fw-bold text-uppercase text-muted d-block mb-2">Link Akses Sertifikat:</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-white border-0 text-primary fw-bold" 
                               id="urlSertif<?= $reg['id_regist'] ?>" 
                               value="<?= $reg['url_sertifikat'] ?>" readonly>
                        <button class="btn btn-success px-3" type="button" onclick="copyToClipboard('urlSertif<?= $reg['id_regist'] ?>')">
                            <i class="bi bi-clipboard"></i> Salin
                        </button>
                    </div>
                </div>

                <div class="d-grid">
                    <a href="<?= $reg['url_sertifikat'] ?>" target="_blank" class="btn btn-outline-success fw-bold rounded-pill py-2">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Buka Link di Tab Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>