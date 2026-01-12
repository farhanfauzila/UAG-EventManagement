<?php
$action = $_GET['action'] ?? 'home';

if ($action === 'login' || $action === 'register' || $action === 'admin_dashboard') {
    return;
}

// Logika cek apakah sekarang di Home atau bukan
$isHome = ($action === 'home' || $action === '');
?>

<style>
    .navbar-uag {
        transition: all 0.4s ease-in-out;
        padding: 15px 0;
        /* Jika bukan home, langsung kasih background solid */
        background:
            <?= $isHome ? 'transparent' : '#1d4372' ?>
            !important;
    }

    /* Gaya saat scroll atau jika bukan di home */
    .navbar-uag.scrolled,
    .navbar-uag.not-home {
        background: #1d4372 !important;
        padding: 10px 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .nav-link {
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-uag <?= !$isHome ? 'not-home' : '' ?>">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?action=home">
            <span style="color: #d1e8fa;">UAG</span> EVENT
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link px-3" href="index.php?action=home">Home</a></li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="index.php?action=katalog&event_status=ongoing">Event</a>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-light btn-sm rounded-pill px-4" href="index.php?action=my_events">
                            <i class="bi bi-person-circle"></i> My Account
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn rounded-pill px-4" href="index.php?action=login"
                            style="background: #d1e8fa; color: #1d4372; font-weight: bold; font-size: 0.8rem;">LOGIN</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    <?php if ($isHome): ?>
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar-uag');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    <?php endif; ?>
</script>