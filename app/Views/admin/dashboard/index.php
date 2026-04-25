<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/admin-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="admin-hero mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-lg-8">
            <div class="small text-white-50 mb-2">Panel Administrasi</div>
            <h1 class="fw-bold mb-2">Dashboard Admin</h1>
            <p class="mb-0">
                Selamat datang, <?= esc($username) ?>. Halaman ini digunakan untuk memantau akun
                dan pengelolaan sistem.
            </p>
        </div>
        <div class="col-lg-4">
            <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 p-3">
                <div class="small text-white-50 mb-1">Akses Aktif</div>
                <div class="fw-semibold fs-5"><?= esc($username) ?></div>
                <div class="small text-white-50 mt-1">Role: Admin</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <a href="<?= base_url('admin/users') ?>"
            class="card admin-stat-card admin-stat-card--primary h-100 text-decoration-none">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="small mb-2">Total User</div>
                    <h2 class="fw-bold mb-2"><?= esc($totalUser) ?></h2>
                    <p class="mb-0 small">Semua akun yang terdaftar di sistem</p>
                </div>
                <div class="admin-stat-icon">👤</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="<?= base_url('admin/users/admin') ?>"
            class="card admin-stat-card admin-stat-card--primary h-100 text-decoration-none">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="small mb-2">Jumlah Admin</div>
                    <h2 class="fw-bold mb-2"><?= esc($totalAdmin) ?></h2>
                    <p class="mb-0 small">Akun dengan akses penuh</p>
                </div>
                <div class="admin-stat-icon">🛡️</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="<?= base_url('admin/users/manajer') ?>"
            class="card admin-stat-card admin-stat-card--primary h-100 text-decoration-none">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="small mb-2">Jumlah Manajer</div>
                    <h2 class="fw-bold mb-2"><?= esc($totalManajer) ?></h2>
                    <p class="mb-0 small">User yang mengelola data operasional</p>
                </div>
                <div class="admin-stat-icon">📋</div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="<?= base_url('admin/users/ceo') ?>"
            class="card admin-stat-card admin-stat-card--primary h-100 text-decoration-none">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="small mb-2">Jumlah CEO</div>
                    <h2 class="fw-bold mb-2"><?= esc($totalCeo) ?></h2>
                    <p class="mb-0 small">User yang memantau dashboard dan laporan</p>
                </div>
                <div class="admin-stat-icon">🏆</div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-8">
        <div class="card admin-panel-card h-100">
            <div class="card-body">
                <h2 class="admin-panel-title">Quick Action</h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card admin-action-card h-100">
                            <div class="card-body">
                                <h5 class="fw-semibold mb-2">Kelola User</h5>
                                <p class="text-muted mb-3">
                                    Tambah, edit, dan atur role user admin, manajer, dan CEO.
                                </p>
                                <a href="<?= base_url('admin/users') ?>" class="btn btn-primary btn-sm">
                                    Buka Kelola User
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card admin-action-card h-100">
                            <div class="card-body">
                                <h5 class="fw-semibold mb-2">Refresh Dashboard</h5>
                                <p class="text-muted mb-3">
                                    Muat ulang ringkasan data user di halaman admin.
                                </p>
                                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-primary btn-sm">
                                    Refresh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card admin-panel-card h-100">
            <div class="card-body">
                <h2 class="admin-panel-title">Informasi Admin</h2>

                <div class="d-grid gap-3">
                    <div class="admin-info-box">
                        <strong>Login Sebagai</strong>
                        <span><?= esc($username) ?></span>
                    </div>

                    <div class="admin-info-box">
                        <strong>Role</strong>
                        <span>Admin</span>
                    </div>

                    <div class="admin-info-box">
                        <strong>Akses Utama</strong>
                        <span>Kelola user dan pengaturan hak akses sistem</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>