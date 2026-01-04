<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UAG Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background: #212529; color: white; }
        .main-content { background: #f8f9fa; min-height: 100vh; }
        .card-stat { border: none; border-radius: 12px; transition: 0.3s; }
        .card-stat:hover { transform: translateY(-5px); }
        .table-box { background: white; border-radius: 15px; overflow: hidden; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0 sidebar d-none d-md-block shadow">
            <div class="p-4 text-center">
                <h4 class="fw-bold text-primary">UAG <span class="text-white">Admin</span></h4>
            </div>
            <div class="list-group list-group-flush mt-3">
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white border-0 py-3 ps-4">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="index.php?action=katalog" class="list-group-item list-group-item-action bg-transparent text-white-50 border-0 py-3 ps-4">
                    <i class="bi bi-calendar-event me-2"></i> Lihat Katalog
                </a>
                <a href="index.php?action=logout" class="list-group-item list-group-item-action bg-transparent text-danger border-0 py-3 ps-4 mt-5">
                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                </a>
            </div>
        </div>

        <div class="col-md-10 p-0 main-content">
            <nav class="navbar navbar-expand-lg navbar-white bg-white px-4 py-3 shadow-sm">
                <span class="navbar-brand fw-bold">Manajemen Pendaftaran</span>
                <div class="ms-auto">
                    <span class="text-muted small">Halo, Admin <strong><?= $_SESSION['nama'] ?></strong></span>
                </div>
            </nav>

            <div class="p-4">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card card-stat shadow-sm bg-primary text-white p-3">
                            <small>Total Pendaftar</small>
                            <h2 class="fw-bold"><?= count($pendaftaran) ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-stat shadow-sm bg-warning text-dark p-3">
                            <small>Menunggu Verifikasi</small>
                            <?php 
                                $waiting = array_filter($pendaftaran, function($r) { 
                                    return $r['status_pembayaran'] === 'pending' && !empty($r['bukti_pembayaran']); 
                                });
                            ?>
                            <h2 class="fw-bold"><?= count($waiting) ?></h2>
                        </div>
                    </div>
                </div>

                <div class="table-box shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Mahasiswa</th>
                                <th>Event</th>
                                <th>Bukti Bayar</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendaftaran as $row): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold"><?= $row['nama_mhs'] ?></div>
                                    <small class="text-muted"><?= $row['id_user'] ?></small>
                                </td>
                                <td><?= $row['nama_event'] ?></td>
                                <td>
                                    <?php if (!empty($row['bukti_pembayaran'])): ?>
                                        <a href="/public/uploads/bukti_pembayaran/<?= $row['bukti_pembayaran'] ?>" target="_blank" class="btn btn-sm btn-outline-info">
    <i class="bi bi-eye"></i> Lihat Bukti
</a>
                                    <?php else: ?>
                                        <span class="text-muted small">Belum Upload</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['status_pembayaran'] === 'paid'): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php elseif (!empty($row['bukti_pembayaran'])): ?>
                                        <span class="badge bg-info">Pending Verif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Unpaid</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status_pembayaran'] === 'pending' && !empty($row['bukti_pembayaran'])): ?>
                                        <a href="index.php?action=approve_payment&id=<?= $row['id_regist'] ?>" class="btn btn-sm btn-success fw-bold me-1" onclick="return confirm('Setujui pembayaran ini?')">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </a>
                                        <a href="index.php?action=reject_payment&id=<?= $row['id_regist'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak pendaftaran ini?')">
                                            Reject
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">No Action</span>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>