<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php

$chartLabels = $chart['labels'] ?? [];
$chartValues = $chart['values'] ?? [];
$chartTitle  = $chart['title'] ?? 'Grafik Kinerja Salesman Terbaik';

$bestSalesmanName = $topSalesman['nama'] ?? '-';
$bestRanking      = $topSalesman['ranking'] ?? '-';

$bestPreference = isset($topSalesman['nilai_preferensi'])
    ? number_format((float)$topSalesman['nilai_preferensi'], 4)
    : '-';

?>

<div class="manager-dashboard-page">

    <!-- ===============================
         HEADER DASHBOARD
    ================================ -->

    <section class="manager-hero mb-4">

        <div class="row align-items-center">

            <div class="col-lg-7">

                <span class="manager-eyebrow">
                    Dashboard Operasional
                </span>

                <h2 class="fw-bold mb-2">
                    Dashboard Manajer
                </h2>

                <p class="mb-0">
                    Ringkasan data salesman,
                    kriteria,
                    penilaian kinerja,
                    dan hasil perhitungan metode
                    TOPSIS berdasarkan periode yang dipilih.
                </p>

            </div>

            <div class="col-lg-5">

                <form method="get" action="<?= base_url('dashboard') ?>" class="manager-filter">

                    <div class="flex-grow-1">

                        <label class="form-label text-white mb-1">

                            Pilih Periode

                        </label>

                        <select name="periode" class="form-select">

                            <?php foreach (($periodeOptions ?? []) as $p): ?>

                            <option value="<?= esc($p) ?>" <?= (($selectedPeriode ?? '') == $p) ? 'selected' : '' ?>>

                                <?= esc($p) ?>

                            </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <button class="btn btn-light px-4 fw-semibold">

                        Tampilkan

                    </button>

                </form>

            </div>

        </div>

    </section>

    <!-- ===============================
         CARD STATISTIK
    ================================ -->

    <div class="row g-3">
        <!-- ===============================
         BARIS 1
    ================================ -->

        <div class="col-md-6 col-xl-3">

            <a href="<?= base_url('salesman') ?>" class="text-decoration-none">

                <div class="manager-stat-card">

                    <div>

                        <p>Total Salesman</p>

                        <h3><?= esc($stats['salesman']) ?></h3>

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

                        <h3><?= esc($stats['kriteria']) ?></h3>

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

                        <h3><?= esc($stats['nilai']) ?></h3>

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

                        <h3><?= esc($stats['hasilAkhir']) ?></h3>

                    </div>

                    <div class="manager-icon-box trophy">

                        <i class="bi bi-trophy-fill"></i>

                    </div>

                </div>

            </a>

        </div>

        <!-- ===============================
         BARIS 2
    ================================ -->

        <div class="col-md-6">

            <div class="manager-stat-card">

                <div>

                    <p>Periode Aktif</p>

                    <h4 class="fw-bold mb-0">

                        <?= esc($selectedPeriode ?? '-') ?>

                    </h4>

                </div>

                <div class="manager-icon-box">

                    <i class="bi bi-calendar-event-fill"></i>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="manager-stat-card">

                <div>

                    <p>Salesman Dinilai</p>

                    <h3><?= count($chartLabels) ?></h3>

                </div>

                <div class="manager-icon-box">

                    <i class="bi bi-person-check-fill"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- ===============================
     GRAFIK + PANEL
================================ -->

    <div class="row g-4 mt-2">
        <!-- ===============================
         GRAFIK
    ================================ -->

        <div class="col-lg-9">

            <div class="manager-card h-100">

                <div class="manager-card-header">

                    <div>

                        <h5 class="fw-bold mb-1">

                            <?= esc($chartTitle) ?>

                        </h5>

                        <p class="text-muted mb-0">

                            Visualisasi nilai preferensi hasil
                            perhitungan metode TOPSIS berdasarkan
                            periode yang dipilih.

                        </p>

                    </div>

                    <?php if (!empty($selectedPeriode)): ?>

                    <span class="badge rounded-pill text-bg-light border">

                        Periode
                        <?= esc($selectedPeriode) ?>

                    </span>

                    <?php endif; ?>

                </div>

                <div class="chart-container">

                    <canvas id="kinerjaChart" data-labels='<?= esc(json_encode($chartLabels), "raw") ?>'
                        data-values='<?= esc(json_encode($chartValues), "raw") ?>'>
                    </canvas>

                </div>

            </div>

        </div>

        <!-- ===============================
         PANEL SALESMAN TERBAIK
    ================================ -->

        <div class="col-lg-3">

            <div class="manager-card h-100">

                <div class="text-center mb-4">

                    <div class="text-muted">
                        Salesman Terbaik
                    </div>

                    <h3 class="fw-bold mt-2">
                        <?= esc($bestSalesmanName) ?>
                    </h3>

                    <span class="badge bg-success px-3 py-2">
                        Ranking #<?= esc($bestRanking) ?>
                    </span>

                </div>


                <!-- KPI CLOSE ORDER -->

                <div class="manager-kpi-card mb-4">

                    <small class="text-muted">
                        Total Close Order
                    </small>

                    <h1 class="fw-bold text-success mt-2 mb-1">

                        <?= $topSalesman['close_order'] ?? 0 ?>

                    </h1>

                    <span class="badge bg-success-subtle text-success">

                        Order

                    </span>

                </div>


                <!-- RINGKASAN -->

                <div class="manager-performance-list">

                    <div class="performance-item">

                        <div>

                            <i class="bi bi-geo-alt-fill text-primary"></i>

                            Pencapaian Kunjungan

                        </div>

                        <strong>

                            <?= $topSalesman['kunjungan'] ?? 0 ?>

                        </strong>

                    </div>


                    <div class="performance-item">

                        <div>

                            <i class="bi bi-bullseye text-warning"></i>

                            Jumlah Demo

                        </div>

                        <strong>

                            <?= $topSalesman['demo'] ?? 0 ?>

                        </strong>

                    </div>


                    <div class="performance-item">

                        <div>

                            <i class="bi bi-star-fill text-success"></i>

                            Nilai Preferensi

                        </div>

                        <strong>

                            <?= $bestPreference ?>

                        </strong>

                    </div>

                </div>

                <hr class="my-4">

                <div class="small text-muted">

                    <div class="d-flex justify-content-between mb-2">

                        <span>Periode</span>

                        <strong><?= esc($selectedPeriode ?? '-') ?></strong>

                    </div>

                    <div class="d-flex justify-content-between mb-2">

                        <span>Salesman Dinilai</span>

                        <strong><?= count($chartLabels) ?></strong>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span>Peringkat</span>

                        <strong>#<?= esc($bestRanking) ?></strong>

                    </div>

                </div>



            </div>

        </div>
    </div>
    <!-- End Manager Card -->

</div>
<!-- End Panel Salesman -->

</div>
<!-- End Row Grafik + Panel -->

</div>
<!-- End Manager Dashboard -->

<?= $this->endSection() ?>