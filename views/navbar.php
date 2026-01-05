<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?action=home">UAG Event Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?action=home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?action=katalog">Event</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=my_events">Event Saya</a></li>
                    <li class="nav-item"><a class="nav-link text-warning" href="index.php?action=logout">Logout (<?= $_SESSION['nama'] ?>)</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=login">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>