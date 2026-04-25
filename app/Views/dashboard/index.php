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

<div class="mb-4">
    <h2 class="fw-bold mb-1">Dashboard</h2>
    <p class="text-muted mb-0">
        Ringkasan data salesman, kriteria, penilaian, dan hasil akhir perhitungan TOPSIS.
    </p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <a href="<?= base_url('salesman') ?>" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 dashboard-stat-card">
                <div class="card-body">
                    <div class="text-muted small mb-1">Salesman</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0 text-dark"><?= esc($stats['salesman'] ?? 0) ?></h3>
                        <span class="fs-4">👤</span>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <span class="btn btn-sm btn-outline-primary w-100">More Info</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="<?= base_url('kriteria') ?>" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 dashboard-stat-card">
                <div class="card-body">
                    <div class="text-muted small mb-1">Kriteria</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0 text-dark"><?= esc($stats['kriteria'] ?? 0) ?></h3>
                        <span class="fs-4">📋</span>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <span class="btn btn-sm btn-outline-primary w-100">More Info</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="<?= base_url('penilaian') ?>" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 dashboard-stat-card">
                <div class="card-body">
                    <div class="text-muted small mb-1">Nilai</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0 text-dark"><?= esc($stats['nilai'] ?? 0) ?></h3>
                        <span class="fs-4">📝</span>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <span class="btn btn-sm btn-outline-primary w-100">More Info</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="<?= base_url('cetak') ?>" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 dashboard-stat-card">
                <div class="card-body">
                    <div class="text-muted small mb-1">Hasil Akhir</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0 text-dark"><?= esc($stats['hasilAkhir'] ?? 0) ?></h3>
                        <span class="fs-4">🏆</span>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <span class="btn btn-sm btn-outline-primary w-100">More Info</span>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-header bg-white">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3">
            <div>
                <h5 class="mb-1 fw-semibold"><?= esc($chartTitle) ?></h5>
                <p class="mb-0 text-muted small">
                    Menampilkan perbandingan nilai preferensi hasil perhitungan TOPSIS berdasarkan periode yang dipilih.
                </p>
            </div>

            <form method="get" action="<?= base_url('dashboard') ?>" class="d-flex gap-2 flex-wrap align-items-end">
                <div>
                    <label for="periode" class="form-label small mb-1">Periode</label>
                    <select name="periode" id="periode" class="form-select form-select-sm">
                        <?php foreach (($periodeOptions ?? []) as $p): ?>
                        <option value="<?= esc($p) ?>" <?= (($selectedPeriode ?? '') === $p) ? 'selected' : '' ?>>
                            <?= esc($p) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card-body">
        <div class="chart-container">
            <canvas id="kinerjaChart" data-labels='<?= esc(json_encode($chartLabels), 'raw') ?>'
                data-values='<?= esc(json_encode($chartValues), 'raw') ?>'></canvas>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Salesman Terbaik</div>
                <div class="fw-semibold"><?= esc($topSalesman) ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Nilai Preferensi Tertinggi</div>
                <div class="fw-semibold">
                    <?= !empty($chartValues) ? esc(number_format((float) $topValue, 4)) : '-' ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Jumlah Data Grafik</div>
                <div class="fw-semibold"><?= esc(count($chartLabels)) ?> Salesman</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Kriteria Terkuat</div>
                <div class="fw-semibold"><?= esc($topCriteriaInsight['nama_kriteria'] ?? '-') ?></div>
                <div class="text-muted small mt-1">
                    Nilai tertinggi: <?= esc($topCriteriaInsight['nilai_tertinggi'] ?? '-') ?>
                </div>
                <div class="text-muted small">
                    Salesman: <?= esc($topCriteriaInsight['salesman'] ?? '-') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>