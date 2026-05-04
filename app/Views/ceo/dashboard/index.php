<?= $this->extend('layout/dashboard') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="fw-bold mb-2">Dashboard CEO Dialihkan</h2>
            <p class="text-muted mb-4">
                Dashboard CEO khusus sudah tidak digunakan. Semua role sekarang memakai dashboard utama.
            </p>

            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">
                Buka Dashboard Utama
            </a>

            <a href="<?= base_url('cetak') ?>" class="btn btn-outline-primary">
                Buka Cetak / Laporan
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>