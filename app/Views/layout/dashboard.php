<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Dashboard') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="<?= base_url('assets/css/base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/components.css') ?>">

    <style>
    .custom-navbar {
        background: linear-gradient(135deg, #f8fafc, #eef6f3);
        z-index: 1030;
    }

    .custom-navbar .navbar-brand {
        color: #0f172a !important;
        font-weight: 700;
    }

    .custom-navbar .nav-link {
        color: #475569 !important;
        font-weight: 500;
        transition: 0.2s ease;
        padding-bottom: 0.45rem;
    }

    .custom-navbar .nav-link:hover {
        color: #0d6efd !important;
    }

    .custom-navbar .nav-link.active {
        color: #0f172a !important;
        font-weight: 700 !important;
        border-bottom: 2px solid #0d6efd;
    }

    .custom-navbar .badge {
        background-color: #ffffff !important;
    }
    </style>

    <?= $this->renderSection('page_css') ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
</head>

<body class="bg-light">
    <?php
    $usernameSession = session()->get('username');
    $displayName = 'User';

    if (isset($username) && is_string($username) && $username !== '') {
        $displayName = $username;
    } elseif (is_string($usernameSession) && $usernameSession !== '') {
        $displayName = $usernameSession;
    }
    ?>

    <nav class="navbar navbar-expand-lg border-bottom shadow-sm sticky-top custom-navbar">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand fw-bold" href="<?= base_url('dashboard') ?>">
                SPK TOPSIS
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <div class="navbar-nav me-auto mb-2 mb-lg-0">

                    <a class="nav-link <?= url_is('dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                        Dashboard
                    </a>

                    <a class="nav-link <?= url_is('salesman*') ? 'active' : '' ?>" href="<?= base_url('salesman') ?>">
                        Data Salesman
                    </a>

                    <a class="nav-link <?= url_is('kriteria*') ? 'active' : '' ?>" href="<?= base_url('kriteria') ?>">
                        Data Kriteria
                    </a>

                    <a class="nav-link <?= url_is('penilaian*') ? 'active' : '' ?>" href="<?= base_url('penilaian') ?>">
                        Penilaian Kinerja
                    </a>

                    <a class="nav-link <?= url_is('perhitungan*') ? 'active' : '' ?>"
                        href="<?= base_url('perhitungan') ?>">
                        Proses Perhitungan
                    </a>

                    <a class="nav-link <?= url_is('cetak*') ? 'active' : '' ?>" href="<?= base_url('cetak') ?>">
                        Cetak
                    </a>

                    <a class="nav-link <?= url_is('pengaturan-user*') ? 'active' : '' ?>"
                        href="<?= base_url('pengaturan-user') ?>">
                        Pengaturan User
                    </a>

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
</body>

</html>