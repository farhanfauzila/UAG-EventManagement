<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Event UAG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php if (isset($_SESSION['user_id'])): ?>
    <span class="me-3">Halo, <strong><?= $_SESSION['nama'] ?></strong>!</span>
    <a href="index.php?action=logout" class="btn btn-sm btn-danger">Logout</a>
<?php else: ?>
    <a href="index.php?action=login" class="btn btn-sm btn-primary">Login</a>
<?php endif; ?>
    <style>
        .card-event {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden; /* Supaya gambar tidak keluar garis rounded */
        }
        .card-event:hover {
            transform: translateY(-5px);
        }
        .img-poster {
            height: 200px;
            object-fit: cover; /* Biar gambar gak penyet kalau ukurannya beda-beda */
            background-color: #ddd; /* Warna abu-abu kalau gambar gak ketemu */
        }
    </style>
</head>
<body class="bg-light">
    <form method="GET" action="index.php" class="row g-3 mb-4">
    <input type="hidden" name="action" value="katalog">

    <!-- Filter Status Event -->
    <div class="col-md-4">
        <select name="event_status" class="form-select">
            <option value="">Semua Status</option>
            <option value="ongoing" <?= ($_GET['event_status'] ?? '') == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
            <option value="canceled" <?= ($_GET['event_status'] ?? '') == 'canceled' ? 'selected' : '' ?>>Canceled</option>
            <option value="completed" <?= ($_GET['event_status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
        </select>
    </div>

    <!-- Filter Tipe Pembayaran -->
    <div class="col-md-4">
        <select name="payment_type" class="form-select">
            <option value="">Semua Tipe</option>
            <option value="free" <?= ($_GET['payment_type'] ?? '') == 'free' ? 'selected' : '' ?>>Gratis</option>
            <option value="paid" <?= ($_GET['payment_type'] ?? '') == 'paid' ? 'selected' : '' ?>>Berbayar</option>
        </select>
    </div>

    <div class="col-md-4">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

    <div class="container py-5">
        <h1 class="text-center mb-5">Daftar Event UAG</h1>
        
        <div class="row g-4"> 
            <?php foreach ($data_events as $e): ?>
                <div class="col-md-4 col-sm-6"> 
                    <div class="card h-100 card-event">
                        <img src="assets/img/<?= $e['poster_event'] ?>" class="card-img-top img-poster" alt="Poster <?= $e['nama_event'] ?>">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-primary"><?= $e['nama_event'] ?></h5>
                            <p class="card-text text-muted mb-1">
                                <strong>Sisa Kuota:</strong> <?= $e['kuota'] ?> orang
                            </p>
                            <p class="card-text text-success fw-bold">
                                Rp <?= number_format($e['harga'], 0, ',', '.') ?>
                            </p>
                            
                            <div class="mt-auto pt-3">
                                <a href="index.php?action=detail&id=<?= $e['id_event'] ?>" class="btn btn-primary w-100">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>