<?= $this->extend('layout/dashboard') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="fw-bold mb-2">Dashboard Admin Dialihkan</h2>
            <p class="text-muted mb-4">
                Dashboard admin khusus sudah tidak digunakan. Semua role sekarang memakai dashboard utama.
            </p>

            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">
                Buka Dashboard Utama
            </a>

            <a href="<?= base_url('pengaturan-user') ?>" class="btn btn-outline-primary">
                Buka Pengaturan User
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>