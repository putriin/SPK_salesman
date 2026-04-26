<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/ceo-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$chartLabels = $chart['labels'] ?? [];
$chartValues = $chart['values'] ?? [];
$chartColors = $chart['colors'] ?? [];
$chartTitle = 'Grafik Nilai Preferensi Salesman';
$topSalesmanName = $topSalesmanSummary['nama'] ?? '-';
$topSalesmanCode = $topSalesmanSummary['kode'] ?? '-';
$topSalesmanValue = (float) ($topSalesmanSummary['preferensi'] ?? 0);
?>

<div class="ceo-hero mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-lg-8">
            <div class="small-muted mb-2">Dashboard Eksekutif</div>
            <h2 class="fw-bold mb-2">Dashboard CEO</h2>
            <p class="mb-0">
                Pantau performa salesman per periode, lihat siapa yang unggul,
                dan pahami indikator terkuat yang mendukung hasil TOPSIS.
            </p>
        </div>

        <div class="col-lg-4 d-flex justify-content-lg-end align-items-center">
            <form method="get" action="<?= base_url('ceo/dashboard') ?>" class="ceo-period-filter">
                <div class="flex-grow-1">
                    <label for="periode" class="form-label small mb-1">Pilih Periode</label>
                    <select name="periode" id="periode" class="form-select">
                        <?php foreach (($periodeOptions ?? []) as $periode): ?>
                        <option value="<?= esc($periode) ?>"
                            <?= (($selectedPeriode ?? '') === $periode) ? 'selected' : '' ?>>
                            <?= esc($periode) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-light fw-semibold px-3">
                    Tampilkan
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card ceo-stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Total Salesman</div>
                    <h3 class="fw-bold mb-0"><?= esc($summary['totalSalesman'] ?? 0) ?></h3>
                </div>
                <div class="ceo-stat-icon">👤</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card ceo-stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Total Kriteria</div>
                    <h3 class="fw-bold mb-0"><?= esc($summary['totalKriteria'] ?? 0) ?></h3>
                </div>
                <div class="ceo-stat-icon">📋</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card ceo-stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Jumlah Periode</div>
                    <h3 class="fw-bold mb-0"><?= esc($summary['totalPeriode'] ?? 0) ?></h3>
                </div>
                <div class="ceo-stat-icon">🗓️</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card ceo-stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Salesman Terbaik</div>
                    <div class="fw-bold fs-5"><?= esc($summary['topSalesman'] ?? '-') ?></div>
                    <div class="small text-muted mt-1">
                        Preferensi:
                        <?= !empty($chartValues) ? esc(number_format((float) ($summary['topPreferensi'] ?? 0), 4)) : '-' ?>
                    </div>
                </div>
                <div class="ceo-stat-icon">🏆</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="card ceo-chart-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1"><?= esc($chartTitle) ?></h5>
                        <p class="text-muted small mb-0">
                            Visualisasi nilai preferensi salesman pada periode terpilih.
                            Batang hijau menunjukkan nilai tertinggi.
                        </p>
                    </div>

                    <span class="badge text-bg-light border">
                        Periode <?= esc($selectedPeriode ?? '-') ?>
                    </span>
                </div>

                <div class="ceo-chart-container">
                    <canvas id="ceoChart"
                        data-labels='<?= json_encode($chartLabels, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'
                        data-values='<?= json_encode($chartValues, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'
                        data-colors='<?= json_encode($chartColors, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'
                        data-title="<?= esc($chartTitle) ?>">
                    </canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card ceo-highlight-card h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Highlight Utama</div>

                <h4 class="fw-bold mb-1"><?= esc($topSalesmanName) ?></h4>
                <div class="text-muted mb-2">Kode: <?= esc($topSalesmanCode) ?></div>

                <div class="ceo-badge-soft mb-4">
                    Nilai Preferensi <?= !empty($chartValues) ? esc(number_format($topSalesmanValue, 4)) : '-' ?>
                </div>

                <div class="ceo-insight-box mb-3">
                    <div class="text-muted small mb-1">Indikator Paling Unggul</div>
                    <div class="fw-bold fs-5 mb-2"><?= esc($topCriteriaInsight['nama_kriteria'] ?? '-') ?></div>
                    <p class="text-muted small mb-0">
                        <?= esc($topCriteriaInsight['deskripsi'] ?? 'Belum ada data insight.') ?>
                    </p>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="ceo-insight-box h-100">
                            <div class="text-muted small mb-1">Salesman</div>
                            <div class="fw-semibold"><?= esc($topCriteriaInsight['salesman'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="ceo-insight-box h-100">
                            <div class="text-muted small mb-1">Nilai Tertinggi</div>
                            <div class="fw-semibold"><?= esc($topCriteriaInsight['nilai_tertinggi'] ?? '-') ?></div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="<?= base_url('ceo/laporan?periode=' . urlencode($selectedPeriode ?? '')) ?>"
                        class="btn btn-primary">
                        Lihat Laporan
                    </a>
                    <a href="<?= base_url('ceo/laporan?periode=' . urlencode($selectedPeriode ?? '')) ?>"
                        class="btn btn-outline-secondary">
                        Cetak / Review Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card ceo-table-card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Ranking Salesman</h5>
                <p class="text-muted small mb-0">
                    Urutan salesman berdasarkan nilai preferensi tertinggi pada periode terpilih.
                </p>
            </div>
            <span class="badge text-bg-primary">
                <?= esc(count($results ?? [])) ?> Data
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Ranking</th>
                        <th style="width: 120px;">Kode</th>
                        <th>Nama</th>
                        <th style="width: 180px;">Nilai Preferensi</th>
                        <th style="width: 140px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($results)): ?>
                    <?php foreach ($results as $row): ?>
                    <tr>
                        <td>
                            <span class="badge bg-primary">#<?= esc($row['ranking']) ?></span>
                        </td>
                        <td><?= esc($row['kode']) ?></td>
                        <td class="fw-medium"><?= esc($row['nama']) ?></td>
                        <td><?= esc(number_format((float) $row['preferensi'], 4)) ?></td>
                        <td>
                            <?php if ((int) $row['ranking'] === 1): ?>
                            <span class="badge text-bg-success">Terbaik</span>
                            <?php else: ?>
                            <span class="badge text-bg-secondary">Peserta</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada data hasil perhitungan untuk periode ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('page_js') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('ceoChart');

    if (!canvas) {
        return;
    }

    if (typeof Chart === 'undefined') {
        return;
    }

    let labels = [];
    let values = [];
    let colors = [];

    try {
        labels = JSON.parse(canvas.getAttribute('data-labels') || '[]');
        values = JSON.parse(canvas.getAttribute('data-values') || '[]');
        colors = JSON.parse(canvas.getAttribute('data-colors') || '[]');
    } catch (error) {
        console.warn('Gagal membaca data chart CEO:', error);
        return;
    }

    if (!Array.isArray(labels) || !Array.isArray(values) || labels.length === 0 || values.length === 0) {
        return;
    }

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nilai Preferensi',
                data: values,
                backgroundColor: colors.length ? colors : labels.map(() =>
                    'rgba(13, 110, 253, 0.75)'),
                borderColor: colors.length ? colors : labels.map(() => 'rgba(13, 110, 253, 1)'),
                borderWidth: 1,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: 1
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Nilai preferensi: ' + Number(context.parsed.y).toFixed(4);
                        }
                    }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>