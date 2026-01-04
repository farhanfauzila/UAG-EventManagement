<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Saya - UAG Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .table-container { background: white; border-radius: 15px; overflow: hidden; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .bg-pending { background-color: #fff3cd; color: #856404; }
        .bg-paid { background-color: #d4edda; color: #155724; }
        .bg-free { background-color: #cce5ff; color: #004085; }
        .bg-info-custom { background-color: #cfe2ff; color: #084298; }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-6 text-center text-md-start">
                <h2 class="fw-bold">Event Saya</h2>
                <p class="text-muted">Kelola pendaftaran dan ambil sertifikat kamu di sini.</p>
            </div>
            <div class="col-md-6 text-center text-md-end align-self-center">
                <a href="index.php?action=katalog" class="btn btn-dark px-4 shadow-sm">Jelajahi Event</a>
            </div>
        </div>

        <div class="table-container shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4 py-3">Informasi Event</th>
                            <th>Waktu Daftar</th>
                            <th>Status Pendaftaran</th>
                            <th>Biaya</th>
                            <th class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($my_registrations)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-calendar2-x display-4 text-muted d-block mb-3"></i>
                                    <p class="text-muted fs-5">Kamu belum mendaftar di event manapun.</p>
                                </td>
                            </tr>
                        <?php else: foreach ($my_registrations as $reg): ?>
                            <?php 
                                // LOGIKA STATUS
                                $is_pending = ($reg['status_pembayaran'] === 'pending');
                                $is_paid_or_free = ($reg['status_pembayaran'] === 'paid' || $reg['status_pembayaran'] === 'free');
                                $sudah_upload = !empty($reg['bukti_pembayaran']);
                                $is_completed = (isset($reg['status_event']) && $reg['status_event'] === 'completed');
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold d-block text-dark fs-6"><?= $reg['nama_event'] ?></span>
                                    <small class="text-muted"><i class="bi bi-geo-alt"></i> <?= $reg['lokasi'] ?></small>
                                </td>
                                <td><?= date('d M Y, H:i', strtotime($reg['waktu_regist'])) ?></td>
                                <td>
                                    <?php if ($is_pending && $sudah_upload): ?>
                                        <span class="status-badge bg-info-custom text-primary"><i class="bi bi-clock-history"></i> Menunggu Verifikasi</span>
                                    <?php elseif ($is_pending): ?>
                                        <span class="status-badge bg-pending text-warning"><i class="bi bi-credit-card"></i> Belum Bayar</span>
                                    <?php elseif ($is_paid_or_free): ?>
                                        <span class="status-badge bg-paid text-success"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
                                        <?php if ($is_completed): ?>
                                            <span class="status-badge bg-dark text-white ms-1">Selesai</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold">
                                    <?= $reg['harga'] == 0 ? '<span class="text-primary">Gratis</span>' : 'Rp '.number_format($reg['harga'], 0, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($is_pending && $sudah_upload): ?>
                                        <button class="btn btn-sm btn-secondary fw-bold" disabled>
                                            <i class="bi bi-hourglass-split"></i> Sedang Diproses
                                        </button>

                                    <?php elseif ($is_pending): ?>
                                        <button class="btn btn-sm btn-warning fw-bold px-3" data-bs-toggle="modal" data-bs-target="#modalBayar<?= $reg['id_regist'] ?>">Bayar</button>
                                        <a href="index.php?action=cancel_reg&id=<?= $reg['id_regist'] ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Batalkan pendaftaran?')">Batal</a>

                                        <div class="modal fade" id="modalBayar<?= $reg['id_regist'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header bg-warning"><h5 class="modal-title fw-bold">Instruksi Pembayaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                                    <form action="index.php?action=upload_bukti" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="id_regist" value="<?= $reg['id_regist'] ?>">
                                                        <div class="modal-body text-start">
                                                            <p class="mb-1 text-muted">Total Pembayaran:</p>
                                                            <h3 class="fw-bold text-primary mb-4">Rp <?= number_format($reg['harga'], 0, ',', '.') ?></h3>
                                                            <div class="p-3 bg-light border rounded mb-4">
                                                                <small class="fw-bold text-muted d-block text-uppercase mb-1">Kirim Ke Rekening:</small>
                                                                <span class="text-dark fs-5 fw-medium"><?= nl2br(htmlspecialchars($reg['info_bayar'])) ?></span>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Upload Bukti Transfer</label>
                                                                <input type="file" name="bukti_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0"><button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Kirim Konfirmasi</button></div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif ($is_paid_or_free): ?>
                                        <?php if ($is_completed): ?>
                                            <button class="btn btn-sm btn-success fw-bold px-3" data-bs-toggle="modal" data-bs-target="#modalSertif<?= $reg['id_regist'] ?>">
                                                <i class="bi bi-patch-check"></i> Ambil Sertifikat
                                            </button>

                                            <div class="modal fade" id="modalSertif<?= $reg['id_regist'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow text-start">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title fw-bold">Sertifikat Tersedia</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Selamat! Kamu telah menyelesaikan event ini. Silakan buka link di bawah ini dan cari nama kamu untuk mengunduh sertifikat.</p>
                                                            <div class="p-3 bg-light border rounded">
                                                                <label class="fw-bold small text-muted text-uppercase d-block mb-2">Link Sertifikat:</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control form-control-sm" id="inputSertif<?= $reg['id_regist'] ?>" value="<?= htmlspecialchars($reg['url_sertifikat'] ?? 'Link belum diatur admin') ?>" readonly>
                                                                    <button class="btn btn-outline-primary btn-sm" type="button" onclick="copyLink('inputSertif<?= $reg['id_regist'] ?>')">Salin</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0"><button class="btn btn-secondary w-100" data-bs-dismiss="modal">Tutup</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-success py-2 px-3 fw-bold"><i class="bi bi-check-circle"></i> Terdaftar</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function copyLink(id) {
        var copyText = document.getElementById(id);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        alert("Link berhasil disalin!");
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>