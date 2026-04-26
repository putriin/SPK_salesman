<!doctype html>
<html lang="id">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    rel="stylesheet">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Dashboard') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/components.css') ?>">
    <?= $this->renderSection('page_css') ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
</head>

<body class="bg-light">
    <?php
    $role = session()->get('role');
    $usernameSession = session()->get('username');
    $displayName = $username ?? $usernameSession ?? 'User';

    $dashboardUrl = 'dashboard';

    if ($role === 'admin') {
        $dashboardUrl = 'admin/dashboard';
    } elseif ($role === 'manajer') {
        $dashboardUrl = 'dashboard';
    } elseif ($role === 'ceo') {
        $dashboardUrl = 'ceo/dashboard';
    }
    ?>

    <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand fw-bold" href="<?= base_url($dashboardUrl) ?>">
                SPK TOPSIS
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <div class="navbar-nav me-auto mb-2 mb-lg-0">

                    <a class="nav-link <?= (url_is($dashboardUrl) ? 'active fw-semibold' : '') ?>"
                        href="<?= base_url($dashboardUrl) ?>">
                        Dashboard
                    </a>

                    <?php if ($role === 'admin'): ?>
                    <a class="nav-link <?= (url_is('admin/users') ? 'active fw-semibold' : '') ?>"
                        href="<?= base_url('admin/users') ?>">
                        Kelola User
                    </a>
                    <?php endif; ?>

                    <?php if ($role === 'manajer'): ?>
                    <a class="nav-link <?= (url_is('salesman') ? 'active fw-semibold' : '') ?>"
                        href="<?= base_url('salesman') ?>">
                        Data Salesman
                    </a>

                    <a class="nav-link <?= (url_is('kriteria') ? 'active fw-semibold' : '') ?>"
                        href="<?= base_url('kriteria') ?>">
                        Data Kriteria
                    </a>

                    <a class="nav-link <?= (url_is('penilaian') ? 'active fw-semibold' : '') ?>"
                        href="<?= base_url('penilaian') ?>">
                        Data Penilaian
                    </a>

                    <a class="nav-link <?= (url_is('perhitungan') ? 'active fw-semibold' : '') ?>"
                        href="<?= base_url('perhitungan') ?>">
                        Proses Perhitungan
                    </a>

                    <a class="nav-link <?= (url_is('cetak') ? 'active fw-semibold' : '') ?>"
                        href="<?= base_url('cetak') ?>">
                        Cetak
                    </a>
                    <?php endif; ?>

                    <?php if ($role === 'ceo'): ?>
                    <a class="nav-link <?= (url_is('ceo/laporan') ? 'active fw-semibold' : '') ?>"
                        href="<?= base_url('ceo/laporan') ?>">
                        Laporan
                    </a>
                    <?php endif; ?>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-bg-light border text-dark px-3 py-2 rounded-pill">
                        <?= esc($displayName) ?>
                    </span>

                    <a class="btn btn-outline-danger btn-sm" href="<?= base_url('logout') ?>">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container-fluid py-4">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('page_js') ?>
    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
</body>

</html>