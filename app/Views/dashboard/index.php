<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$chartLabels = $chart['labels'] ?? [];
$chartValues = $chart['values'] ?? [];
$chartTitle = $chart['title'] ?? 'Grafik Kinerja Salesman Terbaik';

$topSalesman = '-';
$topValue = 0;

if (!empty($chartLabels) && !empty($chartValues)) {
    $maxIndex = array_keys($chartValues, max($chartValues))[0] ?? 0;
    $topSalesman = $chartLabels[$maxIndex] ?? '-';
    $topValue = $chartValues[$maxIndex] ?? 0;
}
?>

<div class="manager-dashboard-page">

    <section class="manager-hero mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="manager-eyebrow">Dashboard Operasional</span>
                <h2 class="fw-bold mb-2">Dashboard Manajer</h2>
                <p class="mb-0">
                    Ringkasan data salesman, kriteria, penilaian, dan hasil akhir perhitungan TOPSIS.
                </p>
            </div>

            <div class="col-lg-5">
                <form method="get" action="<?= base_url('dashboard') ?>" class="manager-filter">
                    <div class="flex-grow-1">
                        <label for="periode" class="form-label text-white mb-1">Pilih Periode</label>
                        <select name="periode" id="periode" class="form-select">
                            <?php foreach (($periodeOptions ?? []) as $p): ?>
                            <option value="<?= esc($p) ?>" <?= (($selectedPeriode ?? '') === $p) ? 'selected' : '' ?>>
                                <?= esc($p) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-light fw-semibold px-4">
                        Tampilkan
                    </button>
                </form>
            </div>
        </div>
    </section>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <a href="<?= base_url('salesman') ?>" class="text-decoration-none">
                <div class="manager-stat-card">
                    <div>
                        <p>Total Salesman</p>
                        <h3><?= esc($stats['salesman'] ?? 0) ?></h3>
                    </div>
                    <div class="manager-icon-box">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="<?= base_url('kriteria') ?>" class="text-decoration-none">
                <div class="manager-stat-card">
                    <div>
                        <p>Total Kriteria</p>
                        <h3><?= esc($stats['kriteria'] ?? 0) ?></h3>
                    </div>
                    <div class="manager-icon-box">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="<?= base_url('penilaian') ?>" class="text-decoration-none">
                <div class="manager-stat-card">
                    <div>
                        <p>Total Penilaian</p>
                        <h3><?= esc($stats['nilai'] ?? 0) ?></h3>
                    </div>
                    <div class="manager-icon-box">
                        <i class="bi bi-clipboard-data-fill"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="<?= base_url('cetak') ?>" class="text-decoration-none">
                <div class="manager-stat-card">
                    <div>
                        <p>Hasil Akhir</p>
                        <h3><?= esc($stats['hasilAkhir'] ?? 0) ?></h3>
                    </div>
                    <div class="manager-icon-box trophy">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="manager-card h-100">
                <div class="manager-card-header">
                    <div>
                        <h5 class="fw-bold mb-1"><?= esc($chartTitle) ?></h5>
                        <p class="text-muted mb-0">
                            Visualisasi nilai preferensi hasil perhitungan TOPSIS berdasarkan periode terpilih.
                        </p>
                    </div>

                    <?php if (!empty($selectedPeriode)): ?>
                    <span class="badge rounded-pill text-bg-light border">
                        Periode <?= esc($selectedPeriode) ?>
                    </span>
                    <?php endif; ?>
                </div>

                <div class="chart-container">
                    <canvas id="kinerjaChart" data-labels='<?= esc(json_encode($chartLabels), 'raw') ?>'
                        data-values='<?= esc(json_encode($chartValues), 'raw') ?>'></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="manager-card h-100">
                <p class="text-muted mb-1">Salesman Terbaik</p>
                <h3 class="fw-bold mb-1"><?= esc($topSalesman) ?></h3>

                <span class="badge rounded-pill text-bg-success mb-3">
                    Nilai Preferensi <?= !empty($chartValues) ? esc(number_format((float) $topValue, 4)) : '-' ?>
                </span>

                <div class="manager-highlight-box">
                    <p class="text-muted mb-1">Kriteria Terkuat</p>
                    <h5 class="fw-bold mb-2"><?= esc($topCriteriaInsight['nama_kriteria'] ?? '-') ?></h5>
                    <p class="mb-1">
                        Nilai tertinggi:
                        <strong><?= esc($topCriteriaInsight['nilai_tertinggi'] ?? '-') ?></strong>
                    </p>
                    <p class="mb-0 text-muted">
                        Salesman: <?= esc($topCriteriaInsight['salesman'] ?? '-') ?>
                    </p>
                </div>

                <div class="manager-note mt-3">
                    Data mengikuti periode yang sedang dipilih dan tidak mengubah proses perhitungan.
                </div>
            </div>
        </div>
    </div>

    <div class="manager-card mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Ringkasan Dashboard</h5>
                <p class="text-muted mb-0">Akses cepat ke menu utama manajer.</p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6 col-xl-3">
                <a href="<?= base_url('salesman') ?>" class="manager-shortcut">
                    <i class="bi bi-person-lines-fill"></i>
                    <span>Kelola Salesman</span>
                </a>
            </div>

            <div class="col-md-6 col-xl-3">
                <a href="<?= base_url('kriteria') ?>" class="manager-shortcut">
                    <i class="bi bi-sliders"></i>
                    <span>Kelola Kriteria</span>
                </a>
            </div>

            <div class="col-md-6 col-xl-3">
                <a href="<?= base_url('penilaian') ?>" class="manager-shortcut">
                    <i class="bi bi-pencil-square"></i>
                    <span>Input Penilaian</span>
                </a>
            </div>

            <div class="col-md-6 col-xl-3">
                <a href="<?= base_url('perhitungan') ?>" class="manager-shortcut">
                    <i class="bi bi-calculator-fill"></i>
                    <span>Proses TOPSIS</span>
                </a>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>