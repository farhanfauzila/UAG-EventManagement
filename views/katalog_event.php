<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Event - UAG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #1d4372;
            --base: #f5f8f7;
            --accent: #d1e8fa;
        }

        body { background-color: var(--base); padding-top: 50px; } 

        .header-section {
            background: linear-gradient(rgba(29, 67, 114, 0.9), rgba(29, 67, 114, 0.9)), url('public/images/latar_event.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 0;
            margin-bottom: -50px;
            border-radius: 0 0 50px 50px;
        }

        .filter-card {
            background: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 25px;
            z-index: 10;
            position: relative;
        }

        .card-event {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .card-event:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(29, 67, 114, 0.15);
        }

        .img-poster {
            height: 220px;
            object-fit: cover;
            transition: 0.5s;
        }

        .card-event:hover .img-poster { transform: scale(1.1); }

        .price-tag {
            background: var(--accent);
            color: var(--primary);
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
        }

        .btn-detail {
            background: var(--primary);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-detail:hover { background: #2a5a96; color: white; transform: scale(1.05); }

        .badge-type {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary);
            padding: 5px 12px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.75rem;
            backdrop-filter: blur(5px);
        }
    </style>
</head>
<body>

    <section class="header-section text-center">
        <div class="container">
            <h1 class="display-4 fw-800 mb-2">Eksplorasi Event</h1>
            <p class="opacity-75">Temukan berbagai seminar, workshop, dan lomba terbaik di kampus UAG.</p>
        </div>
    </section>

    <div class="container">
        <div class="filter-card mb-5">
            <form method="GET" action="index.php" class="row g-3">
                <input type="hidden" name="action" value="katalog">

                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1">KATEGORI</label>
                    <select name="tipe_event" class="form-select border-0 bg-light rounded-3">
                        <option value="">Semua Kategori</option>
                        <?php 
                        $types = ['Seminar', 'Lomba', 'Workshop', 'Summit', 'Festival'];
                        foreach($types as $t): 
                        ?>
                            <option value="<?= $t ?>" <?= ($_GET['tipe_event'] ?? '') == $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1">STATUS</label>
                    <select name="event_status" class="form-select border-0 bg-light rounded-3">
    <option value="" <?= ($status === '') ? 'selected' : '' ?>>Semua Status</option>
    
    <option value="ongoing" <?= ($status === 'ongoing') ? 'selected' : '' ?>>Ongoing</option>
    <option value="completed" <?= ($status === 'completed') ? 'selected' : '' ?>>Completed</option>
    <option value="canceled" <?= ($status === 'canceled') ? 'selected' : '' ?>>Canceled</option>
</select>
                </div>

                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1">BIAYA</label>
                    <select name="payment_type" class="form-select border-0 bg-light rounded-3">
                        <option value="">Semua Tipe</option>
                        <option value="free" <?= ($_GET['payment_type'] ?? '') == 'free' ? 'selected' : '' ?>>Gratis</option>
                        <option value="paid" <?= ($_GET['payment_type'] ?? '') == 'paid' ? 'selected' : '' ?>>Berbayar</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3 shadow-sm py-2">
                        <i class="bi bi-filter-right me-2"></i>Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="row g-4 mb-5"> 
            <?php if (!empty($data_events)): foreach ($data_events as $e): ?>
                <div class="col-md-4 col-sm-6"> 
                    <div class="card h-100 card-event position-relative">
                        <span class="badge-type shadow-sm"><?= $e['tipe_event'] ?></span>
                        <div class="overflow-hidden">
                            <img src="assets/img/<?= $e['poster_event'] ?>" class="card-img-top img-poster" alt="Poster <?= $e['nama_event'] ?>">
                        </div>
                        
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($e['tanggal_mulai'])) ?></small>
                                <span class="price-tag"><?= $e['harga'] == 0 ? 'FREE' : 'Rp '.number_format($e['harga'], 0, ',', '.') ?></span>
                            </div>
                            
                            <h5 class="card-title fw-bold text-dark mb-3"><?= htmlspecialchars($e['nama_event']) ?></h5>
                            
                            <div class="d-flex align-items-center mb-3 text-muted small">
                                <i class="bi bi-people me-2"></i> <span>Sisa Kuota: <strong><?= $e['kuota'] ?></strong> orang</span>
                            </div>

                            <div class="mt-auto">
                                <a href="index.php?action=detail&id=<?= $e['id_event'] ?>" class="btn btn-detail w-100 py-2">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                    <h4 class="text-muted">Oops! Event tidak ditemukan.</h4>
                    <p class="text-muted">Coba ubah filter pencarianmu.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>