<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UAG Event</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png?v=<?= time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-dark: #1d4372;
            --bg-base: #f5f8f7;
            --light-blue: #d1e8fa;
            --accent-color: #0d6efd;
        }

        body {
            background-color: var(--bg-base);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: var(--primary-dark);
            color: white;
            position: fixed;
            width: 16.66667%;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .main-content {
            margin-left: 16.66667%;
            min-height: 100vh;
        }

        .card-event {
            border: none;
            border-radius: 12px;
            background: white;
            transition: all 0.3s ease;
            border-bottom: 4px solid var(--light-blue);
        }

        .card-event:hover {
            transform: translateY(-5px);
            border-bottom-color: var(--primary-dark);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .accordion-item {
            border: none;
            border-radius: 12px !important;
            overflow: hidden;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .accordion-button {
            background-color: white;
            color: var(--primary-dark);
            font-weight: 700;
            border: none;
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--light-blue);
            color: var(--primary-dark);
            box-shadow: none;
        }

        .accordion-button::after {
            filter: brightness(0.2);
        }

        .table-box {
            background: white;
            border-radius: 0 0 12px 12px;
            overflow: hidden;
        }

        .navbar {
            background: white;
            border-bottom: 1px solid #e0e0e0;
        }

        .nav-link {
            color: rgba(255, 255, 255, .8);
            border-radius: 8px;
            margin: 5px 15px;
            transition: 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--light-blue);
            color: var(--primary-dark) !important;
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--primary-dark);
            border: none;
        }

        .btn-primary:hover {
            background-color: #153256;
        }

        .badge-count {
            background: var(--primary-dark);
            color: white;
            border-radius: 6px;
            padding: 2px 8px;
            font-size: 0.75rem;
            vertical-align: middle;
        }

        .icon-box {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.5);
        }

        .btn-action-floating {
            position: absolute;
            right: 8px;
            z-index: 10;
            padding: 2px 5px;
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-md-2 sidebar d-none d-md-block">
                <div class="p-4 text-center">
                    <h4 class="fw-bold" style="color: var(--light-blue)">Admin <span class="text-white">UAG</span></h4>
                </div>
                <nav class="nav flex-column mt-3">
                    <a href="#section-keuangan" class="nav-link"><i class="bi bi-cash-stack me-2"></i> Ringkasan
                        Keuangan</a>
                    <a href="#section-verifikasi" class="nav-link"><i class="bi bi-shield-check me-2"></i>
                        Verifikasi</a>



                    <a href="#section-events" class="nav-link"><i class="bi bi-grid me-2"></i> Manajemen Event</a>

                    <div class="mt-5 pt-5">
                        <a href="index.php?action=logout" class="nav-link text-danger fw-bold"><i
                                class="bi bi-box-arrow-left me-2"></i> Logout</a>
                    </div>
                </nav>
            </div>

            <div class="col-md-10 main-content">
                <nav class="navbar navbar-expand px-4 py-3 sticky-top">
                    <div class="container-fluid">
                        <h5 class="fw-bold mb-0 text-dark">Dashboard Control Panel</h5>
                        <div class="ms-auto">
                            <button class="btn btn-primary btn-sm fw-bold rounded-pill px-4 shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#modalTambahEvent">
                                <i class="bi bi-plus-lg me-1"></i> Buat Event Baru
                            </button>
                        </div>
                    </div>
                </nav>

                <div class="p-4">
                    <div class="row mb-4 g-3">
                        <div class="col-md-3">
                            <div class="card p-3 border-0 shadow-sm"
                                style="background: var(--light-blue); border-left: 5px solid var(--primary-dark) !important;">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box me-3 text-primary">
                                        <i class="bi bi-people-fill fs-3"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted fw-bold d-block" style="font-size: 0.7rem;">TOTAL
                                            PESERTA</small>
                                        <h3 class="fw-bold mb-0" style="color: var(--primary-dark)">
                                            <?= count($pendaftaran) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card p-3 border-0 shadow-sm"
                                style="background: white; border-left: 5px solid var(--light-blue) !important;">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box me-3 text-primary">
                                        <i class="bi bi-calendar-event fs-3" style="color: var(--primary-dark)"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted fw-bold d-block" style="font-size: 0.7rem;">TOTAL
                                            EVENT</small>
                                        <h3 class="fw-bold mb-0" style="color: var(--primary-dark)">
                                            <?= isset($events) ? count($events) : 0 ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 id="section-keuangan" class="fw-bold mb-4 text-dark mt-5"><i
                            class="bi bi-cash-stack me-2"></i>Ringkasan Keuangan Event</h5>
                    <div class="card border-0 shadow-sm mb-5" style="border-radius: 12px; overflow: hidden;">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background: var(--primary-dark); color: white;">
                                    <tr>
                                        <th class="ps-4 py-3">Nama Event</th>
                                        <th class="text-center">Peserta Lunas</th>
                                        <th class="text-center">Sisa Kuota</th>
                                        <th class="pe-4 text-end">Total Uang Masuk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $grandTotal = 0;

                                    if (!empty($statistik)):
                                        foreach ($statistik as $s):
                                            $uangEvent = (float) ($s['total_uang_masuk'] ?? 0);
                                            $grandTotal += $uangEvent;
                                            ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-dark">
                                                    <?= htmlspecialchars($s['nama_event'] ?? 'Event Tanpa Nama') ?></td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-primary border border-primary px-3">
                                                        <?= (int) ($s['peserta_lunas'] ?? 0) ?> Orang
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge <?= ($s['sisa_kuota'] ?? 0) <= 5 ? 'bg-danger' : 'bg-secondary' ?>">
                                                        <?= (int) ($s['sisa_kuota'] ?? 0) ?> Slot
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-end fw-bold text-success">
                                                    Rp <?= number_format($uangEvent, 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data keuangan.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot style="background: var(--light-blue);">
                                    <tr>
                                        <td colspan="3" class="ps-4 fw-bold text-dark py-3">TOTAL PENDAPATAN KESELURUHAN
                                        </td>
                                        <td class="pe-4 text-end fw-bold text-primary fs-5">
                                            Rp <?= number_format($grandTotal, 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <h5 id="section-verifikasi" class="fw-bold mb-4 text-dark"><i class="bi bi-people me-2"></i>Daftar
                        Peserta per Event</h5>

                    <?php
                    $grouped = [];
                    if (!empty($pendaftaran)) {
                        foreach ($pendaftaran as $row) {
                            $grouped[$row['nama_event']][] = $row;
                        }
                    }
                    ?>

                    <div class="accordion mb-5" id="accordionVerifikasi">
                        <?php if (empty($grouped)): ?>
                            <div class="alert alert-light border shadow-sm text-center">Belum ada pendaftaran mahasiswa.
                            </div>
                        <?php else:
                            $counter = 0;
                            foreach ($grouped as $eventName => $pesertaList):
                                $counter++;
                                $isFirst = ($counter === 1) ? 'show' : '';
                                $isCollapsed = ($counter === 1) ? '' : 'collapsed';
                                ?>
                                <div class="accordion-item shadow-sm">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $isCollapsed ?>" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse<?= $counter ?>">
                                            <i class="bi bi-bookmark-fill me-2" style="color: var(--primary-dark);"></i>
                                            <span class="me-2"><?= $eventName ?></span>
                                            <span class="badge-count"><?= count($pesertaList) ?> Peserta</span>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $counter ?>" class="accordion-collapse collapse <?= $isFirst ?>"
                                        data-bs-parent="#accordionVerifikasi">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="small text-uppercase" style="background: var(--bg-base)">
                                                        <tr>
                                                            <th class="ps-4">Mahasiswa</th>
                                                            <th>Bukti Bayar</th>
                                                            <th>Status</th>
                                                            <th class="text-center">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($pesertaList as $row): ?>
                                                            <tr>
                                                                <td class="ps-4">
                                                                    <div class="fw-bold text-dark"><?= $row['nama_mhs'] ?></div>
                                                                    <small class="text-muted">ID: <?= $row['id_user'] ?></small>
                                                                </td>
                                                                <td>
                                                                    <?php if ($row['status_pembayaran'] === 'free'): ?>
                                                                        <span class="text-muted small">- Gratis -</span>
                                                                    <?php elseif (!empty($row['bukti_pembayaran'])): ?>
                                                                        <a href="public/uploads/bukti_pembayaran/<?= $row['bukti_pembayaran'] ?>"
                                                                            target="_blank"
                                                                            class="btn btn-sm btn-outline-primary py-0 px-2 small"><i
                                                                                class="bi bi-file-earmark-image"></i> Cek</a>
                                                                    <?php else: ?>
                                                                        <span class="text-muted smaller italic">Menunggu...</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge rounded-pill <?= ($row['status_pembayaran'] === 'paid' || $row['status_pembayaran'] === 'free') ? 'bg-success' : 'bg-warning text-dark' ?>"
                                                                        style="font-size: 0.7rem;">
                                                                        <?= strtoupper($row['status_pembayaran']) ?>
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php if ($row['status_pembayaran'] === 'pending' && !empty($row['bukti_pembayaran'])): ?>
                                                                        <a href="index.php?action=approve_payment&id=<?= $row['id_regist'] ?>"
                                                                            class="btn btn-sm btn-success p-1 rounded-circle"><i
                                                                                class="bi bi-check"></i></a>
                                                                        <a href="index.php?action=reject_payment&id=<?= $row['id_regist'] ?>"
                                                                            class="btn btn-sm btn-danger p-1 rounded-circle"><i
                                                                                class="bi bi-x"></i></a>
                                                                    <?php else: ?>
                                                                        <i class="bi bi-dash-circle text-muted small"></i>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; endif; ?>
                    </div>

                    <h5 id="section-events" class="fw-bold mb-4 text-dark"><i class="bi bi-grid-fill me-2"></i>Manajemen
                        Event</h5>
                    <div class="row g-4">
                        <?php if (isset($events) && count($events) > 0):
                            foreach ($events as $e): ?>
                                <?php
                                // Logika Otomatis: Cek apakah tanggal selesai sudah lewat dari hari ini
                                $hari_ini = date('Y-m-d');
                                $sudah_lewat = ($e['tanggal_selesai'] < $hari_ini);

                                if ($e['status_event'] === 'canceled') {
                                    $status_final = 'canceled';
                                } elseif ($sudah_lewat || $e['status_event'] === 'completed') {
                                    $status_final = 'completed';
                                } else {
                                    $status_final = 'ongoing';
                                }
                                ?>

                                <div class="col-md-6 col-lg-4">
                                    <div class="card card-event p-3 shadow-sm h-100 position-relative">
                                        <button class="btn btn-sm btn-outline-primary border-0 btn-action-floating"
                                            style="top: 8px; right: 8px; position: absolute; z-index: 10;"
                                            data-bs-toggle="modal" data-bs-target="#modalEditEvent<?= $e['id_event'] ?>">
                                            <i class="bi bi-pencil-square" style="font-size: 0.85rem;"></i>
                                        </button>

                                        <a href="index.php?action=delete_event&id=<?= $e['id_event'] ?>"
                                            class="btn btn-sm btn-outline-danger border-0 btn-action-floating"
                                            style="top: 38px; right: 8px; position: absolute; z-index: 10;"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus event ini secara permanen?')">
                                            <i class="bi bi-trash" style="font-size: 0.85rem;"></i>
                                        </a>

                                        <div class="d-flex justify-content-between align-items-start mb-3 pe-5">
                                            <span class="badge"
                                                style="background: var(--light-blue); color: var(--primary-dark)"><?= $e['tipe_event'] ?></span>

                                            <?php if ($status_final === 'canceled'): ?>
                                                <span class="badge bg-danger rounded-pill">Canceled</span>
                                            <?php elseif ($status_final === 'completed'): ?>
                                                <span class="badge bg-success rounded-pill">Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-info text-dark rounded-pill">Ongoing</span>
                                            <?php endif; ?>
                                        </div>

                                        <h6 class="fw-bold text-dark mb-1 pe-5 text-truncate">
                                            <?= htmlspecialchars($e['nama_event'] ?? 'Tanpa Nama') ?></h6>
                                        <div class="small text-muted mb-3"><i class="bi bi-geo-alt me-1"></i><?= $e['lokasi'] ?>
                                        </div>

                                        <div class="mt-auto pt-3 border-top">
                                            <?php if ($status_final === 'completed'): ?>
                                                <label class="small fw-bold mb-2 text-success"><i
                                                        class="bi bi-patch-check me-1"></i>Link Sertifikat:</label>
                                                <form action="index.php?action=upload_sertifikat" method="POST"
                                                    class="input-group input-group-sm">
                                                    <input type="hidden" name="id_event" value="<?= $e['id_event'] ?>">
                                                    <input type="text" name="url_sertifikat" class="form-control"
                                                        placeholder="G-Drive Link..." value="<?= $e['url_sertifikat'] ?? '' ?>">
                                                    <button class="btn btn-dark" type="submit"><i class="bi bi-send"></i></button>
                                                </form>
                                            <?php else: ?>
                                                <div class="text-center bg-light rounded py-2">
                                                    <small class="text-muted italic small">Sertifikat aktif otomatis jika tanggal
                                                        selesai terlampaui</small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modalEditEvent<?= $e['id_event'] ?>" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header border-0 p-4"
                                                style="background: var(--primary-dark); color: white;">
                                                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit
                                                    Event</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="index.php?action=update_event" method="POST"
                                                enctype="multipart/form-data">
                                                <input type="hidden" name="id_event" value="<?= $e['id_event'] ?>">
                                                <div class="modal-body p-4 text-start">
                                                    <div class="row g-3">
                                                        <div class="col-md-8">
                                                            <label class="form-label fw-bold small">NAMA EVENT</label>
                                                            <input type="text" name="nama_event"
                                                                class="form-control text-dark fw-bold"
                                                                value="<?= $e['nama_event'] ?>" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold small">STATUS ACARA</label>
                                                            <select name="status_event"
                                                                class="form-select border-primary fw-bold text-primary">
                                                                <option value="ongoing"
                                                                    <?= $e['status_event'] == 'ongoing' ? 'selected' : '' ?>>Ongoing
                                                                </option>
                                                                <option value="completed"
                                                                    <?= $e['status_event'] == 'completed' ? 'selected' : '' ?>>
                                                                    Completed</option>
                                                                <option value="canceled"
                                                                    <?= $e['status_event'] == 'canceled' ? 'selected' : '' ?>>Canceled
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold small">LOKASI</label>
                                                            <input type="text" name="lokasi" class="form-control"
                                                                value="<?= $e['lokasi'] ?>" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold small">KUOTA</label>
                                                            <input type="number" name="kuota" class="form-control"
                                                                value="<?= $e['kuota'] ?>" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold small">HARGA (Rp)</label>
                                                            <input type="number" name="harga" class="form-control"
                                                                value="<?= $e['harga'] ?>" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-bold small">GANTI POSTER</label>
                                                            <input type="file" name="poster_event" class="form-control"
                                                                accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 p-4">
                                                    <button type="button" class="btn btn-light px-4"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; endif; ?>


                        <div class="modal fade" id="modalTambahEvent" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header border-0 p-4"
                                        style="background: var(--primary-dark); color: white;">
                                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Buat Event
                                            Baru</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="index.php?action=create_event" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold small">NAMA EVENT</label>
                                                    <input type="text" name="nama_event" class="form-control" required
                                                        placeholder="Masukkan nama acara...">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small">TIPE EVENT</label>
                                                    <select name="tipe_event" class="form-select">
                                                        <option value="Seminar">Seminar</option>
                                                        <option value="Workshop">Workshop</option>
                                                        <option value="Lomba">Lomba</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small">LOKASI</label>
                                                    <input type="text" name="lokasi" class="form-control" required
                                                        placeholder="Ruangan/Zoom">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small">TANGGAL MULAI</label>
                                                    <input type="date" name="tanggal_mulai" class="form-control"
                                                        required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small">TANGGAL SELESAI</label>
                                                    <input type="date" name="tanggal_selesai" class="form-control"
                                                        required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold small">KUOTA</label>
                                                    <input type="number" name="kuota" class="form-control" value="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold small">HARGA (0 jika
                                                        gratis)</label>
                                                    <input type="number" name="harga" class="form-control" value="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold small">POSTER</label>
                                                    <input type="file" name="poster_event" class="form-control"
                                                        accept="image/*">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold small">DESKRIPSI</label>
                                                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 p-4">
                                            <button type="button" class="btn btn-light px-4"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow">Simpan
                                                Event</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script
                            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>