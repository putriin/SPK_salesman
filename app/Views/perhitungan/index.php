<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/perhitungan.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$alternativesRows = isset($alternatives) && is_array($alternatives) ? $alternatives : [];
$criteriaRows = isset($criteria) && is_array($criteria) ? $criteria : [];
$resultsRows = isset($results) && is_array($results) ? $results : [];
$periodRows = isset($periodOptions) && is_array($periodOptions) ? $periodOptions : [];
$weightsRows = isset($weights) && is_array($weights) ? $weights : [];
$normalizedRows = isset($normalized) && is_array($normalized) ? $normalized : [];
$weightedRows = isset($weighted) && is_array($weighted) ? $weighted : [];
$idealPositiveRows = isset($idealPositive) && is_array($idealPositive) ? $idealPositive : [];
$idealNegativeRows = isset($idealNegative) && is_array($idealNegative) ? $idealNegative : [];
$incompleteRows = isset($incompleteAlternatives) && is_array($incompleteAlternatives) ? $incompleteAlternatives : [];

$selectedPeriodeValue = isset($selectedPeriode) ? (string) $selectedPeriode : '';
$lastCalculatedAtValue = isset($lastCalculatedAt) ? (string) $lastCalculatedAt : '';
$currentCompleteCountValue = isset($currentCompleteCount) ? (int) $currentCompleteCount : 0;
$currentCriteriaCountValue = isset($currentCriteriaCount) ? (int) $currentCriteriaCount : 0;
$validAlternativeCountValue = isset($validAlternativeCount) ? (int) $validAlternativeCount : count($alternativesRows);

$hasCriteria = count($criteriaRows) > 0;
$hasEnoughAlternatives = $validAlternativeCountValue >= 2;
$hasResults = !empty($resultsRows);
$hasSnapshotValue = !empty($hasSnapshot);
$canProcessValue = !empty($canProcess);
$isStaleValue = !empty($isStale);

$winnerData = isset($winner) && is_array($winner) ? $winner : [];
$winnerName = isset($winnerData['nama']) ? (string) $winnerData['nama'] : '-';
$winnerPreference = isset($winnerData['preferensi']) ? (float) $winnerData['preferensi'] : 0;
?>

<section class="card border-0 shadow-sm">
    <div class="card-header bg-light border-bottom">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                ←
            </a>

            <h5 class="mb-0 fw-semibold text-dark">
                Proses Perhitungan
            </h5>
        </div>
    </div>

    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc((string) session()->getFlashdata('success')) ?>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc((string) session()->getFlashdata('error')) ?>
        </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="periode" class="form-label fw-semibold">Pilih Periode</label>

                        <form method="get" action="<?= base_url('perhitungan') ?>" class="d-flex gap-2">
                            <select name="periode" id="periode" class="form-select">
                                <?php if (!empty($periodRows)): ?>
                                <?php foreach ($periodRows as $option): ?>
                                <?php $optionValue = (string) $option; ?>

                                <option value="<?= esc($optionValue) ?>"
                                    <?= $selectedPeriodeValue === $optionValue ? 'selected' : '' ?>>
                                    <?= esc($optionValue) ?>
                                </option>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <option value="">Belum ada periode</option>
                                <?php endif; ?>
                            </select>

                            <button type="submit" class="btn btn-primary">
                                Tampilkan
                            </button>
                        </form>
                    </div>

                    <div class="col-md-8">
                        <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                            <?php if ($selectedPeriodeValue !== '' && !empty($periodRows)): ?>
                            <form method="post" action="<?= base_url('perhitungan/process') ?>"
                                onsubmit="return confirm('Perhitungan TOPSIS untuk periode <?= esc($selectedPeriodeValue) ?> akan diproses ulang sesuai data penilaian terbaru. Lanjutkan?')">
                                <?= csrf_field() ?>

                                <input type="hidden" name="periode" value="<?= esc($selectedPeriodeValue) ?>">

                                <button type="submit" class="btn btn-success"
                                    <?= !$canProcessValue ? 'disabled' : '' ?>>
                                    <?= $hasSnapshotValue ? 'Proses Ulang / Update Hasil' : 'Proses TOPSIS' ?>
                                </button>
                            </form>
                            <?php endif; ?>

                            <?php if ($hasSnapshotValue): ?>
                            <a href="<?= base_url('cetak?periode=' . urlencode($selectedPeriodeValue)) ?>"
                                class="btn btn-outline-primary">
                                Lihat Versi Cetak
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="mt-3 small text-muted">
                    Halaman ini menampilkan detail hasil perhitungan yang <strong>sudah disimpan</strong> per periode.
                    Jika data penilaian berubah, klik <strong>Proses Ulang / Update Hasil</strong> agar hasil dan
                    riwayat sinkron kembali.
                </div>
            </div>
        </div>

        <?php if ($hasSnapshotValue && $lastCalculatedAtValue !== ''): ?>
        <div class="alert alert-secondary mb-4">
            Hasil yang sedang ditampilkan adalah hasil tersimpan untuk periode
            <strong><?= esc($selectedPeriodeValue) ?></strong>.
            Terakhir dihitung pada <strong><?= esc($lastCalculatedAtValue) ?></strong>.
        </div>
        <?php endif; ?>

        <?php if ($isStaleValue): ?>
        <div class="alert alert-warning mb-4">
            Data penilaian periode <strong><?= esc($selectedPeriodeValue) ?></strong> sudah berubah setelah
            perhitungan terakhir. Klik <strong>Proses Ulang / Update Hasil</strong> untuk memperbarui hasil dan
            riwayat perhitungan.
        </div>
        <?php endif; ?>

        <?php if (!$hasSnapshotValue): ?>
        <div class="alert alert-info mb-4">
            Belum ada hasil perhitungan tersimpan untuk periode
            <strong><?= esc($selectedPeriodeValue) ?></strong>.

            <?php if ($canProcessValue): ?>
            Data sudah siap diproses. Klik tombol <strong>Proses TOPSIS</strong> di atas untuk menyimpan hasil
            perhitungan periode ini.
            <?php else: ?>
            Data belum siap diproses. Pastikan minimal ada 2 salesman dengan penilaian lengkap pada periode ini.
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Periode</div>
                        <div class="fw-semibold"><?= esc($selectedPeriodeValue) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Salesman Lengkap Saat Ini</div>
                        <div class="fw-semibold"><?= esc((string) $currentCompleteCountValue) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Jumlah Kriteria Saat Ini</div>
                        <div class="fw-semibold"><?= esc((string) $currentCriteriaCountValue) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($winnerData)): ?>
        <div class="alert alert-success mb-4">
            <h5 class="fw-semibold mb-2">Hasil Utama</h5>
            <p class="mb-0">
                Salesman terbaik periode <strong><?= esc($selectedPeriodeValue) ?></strong> adalah
                <strong><?= esc($winnerName) ?></strong>
                dengan nilai preferensi
                <strong><?= number_format($winnerPreference, 4) ?></strong>.
            </p>
        </div>
        <?php endif; ?>

        <?php if (!empty($incompleteRows)): ?>
        <div class="alert alert-warning mb-4">
            <h5 class="fw-semibold mb-2">Perhatian</h5>
            <p class="mb-2">
                Salesman berikut belum ikut dihitung karena data penilaiannya pada periode ini belum lengkap untuk semua
                kriteria:
            </p>

            <ul class="mb-0">
                <?php foreach ($incompleteRows as $item): ?>
                <?php
                        $itemKode = is_array($item) && isset($item['kode']) ? (string) $item['kode'] : '-';
                        $itemNama = is_array($item) && isset($item['nama']) ? (string) $item['nama'] : '-';
                        ?>
                <li><?= esc($itemKode) ?> - <?= esc($itemNama) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (!$hasCriteria || !$hasEnoughAlternatives || !$hasResults): ?>
        <div class="alert alert-info mb-0">
            <h5 class="fw-semibold mb-2">Data Belum Siap</h5>

            <?php if (!$hasCriteria): ?>
            <p class="mb-0">
                Perhitungan TOPSIS belum bisa dijalankan karena data kriteria belum tersedia.
            </p>
            <?php elseif (!$hasEnoughAlternatives): ?>
            <p class="mb-0">
                Perhitungan TOPSIS belum bisa dijalankan karena salesman dengan penilaian lengkap
                pada periode <strong><?= esc($selectedPeriodeValue) ?></strong> baru berjumlah
                <strong><?= esc((string) $currentCompleteCountValue) ?></strong>.
                Minimal dibutuhkan <strong>2 salesman</strong> dengan data lengkap.
            </p>
            <?php else: ?>
            <p class="mb-0">
                Hasil perhitungan untuk periode ini belum tersedia.
                Silakan jalankan proses TOPSIS untuk menyimpan dan menampilkan detail hasilnya.
            </p>
            <?php endif; ?>
        </div>
        <?php else: ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-1 fw-semibold">Step 1 — Matriks Keputusan</h5>
                <p class="mb-0 text-muted small">Ini adalah nilai awal dari setiap salesman untuk setiap kriteria.</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width:100px;">Kode</th>
                            <th>Salesman</th>
                            <?php foreach ($criteriaRows as $criterion): ?>
                            <?php $criterionKode = is_array($criterion) && isset($criterion['kode']) ? (string) $criterion['kode'] : '-'; ?>
                            <th><?= esc($criterionKode) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($alternativesRows as $alt): ?>
                        <?php
                                $altKode = is_array($alt) && isset($alt['kode']) ? (string) $alt['kode'] : '-';
                                $altNama = is_array($alt) && isset($alt['nama']) ? (string) $alt['nama'] : '-';
                                $altScores = is_array($alt) && isset($alt['scores']) && is_array($alt['scores']) ? $alt['scores'] : [];
                                ?>

                        <tr>
                            <td class="text-center"><?= esc($altKode) ?></td>
                            <td><?= esc($altNama) ?></td>

                            <?php foreach ($altScores as $score): ?>
                            <td class="text-center"><?= esc((string) $score) ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-1 fw-semibold">Step 2 — Bobot Kriteria</h5>
                <p class="mb-0 text-muted small">Bobot awal dinormalisasi supaya total bobot menjadi 1.</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width:100px;">Kode</th>
                            <th>Nama Kriteria</th>
                            <th style="width:140px;">Tipe</th>
                            <th style="width:160px;">Bobot Awal</th>
                            <th style="width:180px;">Bobot Normalisasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($criteriaRows as $index => $criterion): ?>
                        <?php
                                $criterionKode = is_array($criterion) && isset($criterion['kode']) ? (string) $criterion['kode'] : '-';
                                $criterionNama = is_array($criterion) && isset($criterion['nama']) ? (string) $criterion['nama'] : '-';
                                $criterionTipe = is_array($criterion) && isset($criterion['tipe']) ? (string) $criterion['tipe'] : '-';
                                $criterionBobot = is_array($criterion) && isset($criterion['bobot']) ? (string) $criterion['bobot'] : '0';
                                $weightValue = isset($weightsRows[$index]) ? (float) $weightsRows[$index] : 0;
                                ?>

                        <tr>
                            <td class="text-center"><?= esc($criterionKode) ?></td>
                            <td><?= esc($criterionNama) ?></td>
                            <td class="text-center"><?= esc(ucfirst($criterionTipe)) ?></td>
                            <td class="text-center"><?= esc($criterionBobot) ?></td>
                            <td class="text-center"><?= number_format($weightValue, 4) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-1 fw-semibold">Step 3 — Matriks Ternormalisasi</h5>
                <p class="mb-0 text-muted small">Setiap nilai dibagi dengan akar jumlah kuadrat pada kolom yang sama.
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width:100px;">Kode</th>
                            <th>Salesman</th>
                            <?php foreach ($criteriaRows as $criterion): ?>
                            <?php $criterionKode = is_array($criterion) && isset($criterion['kode']) ? (string) $criterion['kode'] : '-'; ?>
                            <th><?= esc($criterionKode) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($alternativesRows as $rowIndex => $alt): ?>
                        <?php
                                $altKode = is_array($alt) && isset($alt['kode']) ? (string) $alt['kode'] : '-';
                                $altNama = is_array($alt) && isset($alt['nama']) ? (string) $alt['nama'] : '-';
                                $normalValues = isset($normalizedRows[$rowIndex]) && is_array($normalizedRows[$rowIndex]) ? $normalizedRows[$rowIndex] : [];
                                ?>

                        <tr>
                            <td class="text-center"><?= esc($altKode) ?></td>
                            <td><?= esc($altNama) ?></td>

                            <?php foreach ($normalValues as $value): ?>
                            <td class="text-center"><?= number_format((float) $value, 4) ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-1 fw-semibold">Step 4 — Matriks Ternormalisasi Berbobot</h5>
                <p class="mb-0 text-muted small">Nilai normalisasi dikalikan bobot normalisasi tiap kriteria.</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width:100px;">Kode</th>
                            <th>Salesman</th>
                            <?php foreach ($criteriaRows as $criterion): ?>
                            <?php $criterionKode = is_array($criterion) && isset($criterion['kode']) ? (string) $criterion['kode'] : '-'; ?>
                            <th><?= esc($criterionKode) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($alternativesRows as $rowIndex => $alt): ?>
                        <?php
                                $altKode = is_array($alt) && isset($alt['kode']) ? (string) $alt['kode'] : '-';
                                $altNama = is_array($alt) && isset($alt['nama']) ? (string) $alt['nama'] : '-';
                                $weightedValues = isset($weightedRows[$rowIndex]) && is_array($weightedRows[$rowIndex]) ? $weightedRows[$rowIndex] : [];
                                ?>

                        <tr>
                            <td class="text-center"><?= esc($altKode) ?></td>
                            <td><?= esc($altNama) ?></td>

                            <?php foreach ($weightedValues as $value): ?>
                            <td class="text-center"><?= number_format((float) $value, 4) ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-1 fw-semibold">Step 5 — Solusi Ideal Positif dan Negatif</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width:140px;">Keterangan</th>
                            <?php foreach ($criteriaRows as $criterion): ?>
                            <?php $criterionKode = is_array($criterion) && isset($criterion['kode']) ? (string) $criterion['kode'] : '-'; ?>
                            <th><?= esc($criterionKode) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="text-center fw-semibold">A+</td>
                            <?php foreach ($idealPositiveRows as $value): ?>
                            <td class="text-center"><?= number_format((float) $value, 4) ?></td>
                            <?php endforeach; ?>
                        </tr>

                        <tr>
                            <td class="text-center fw-semibold">A-</td>
                            <?php foreach ($idealNegativeRows as $value): ?>
                            <td class="text-center"><?= number_format((float) $value, 4) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-1 fw-semibold">Step 6 — Nilai Preferensi dan Ranking</h5>
                <p class="mb-0 text-muted small">Semakin besar nilai preferensi, semakin tinggi ranking-nya.</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width:120px;">Ranking</th>
                            <th style="width:100px;">Kode</th>
                            <th>Salesman</th>
                            <th style="width:140px;">D+</th>
                            <th style="width:140px;">D-</th>
                            <th style="width:180px;">Nilai Preferensi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($resultsRows as $result): ?>
                        <?php
                                $ranking = is_array($result) && isset($result['ranking']) ? (string) $result['ranking'] : '-';
                                $kode = is_array($result) && isset($result['kode']) ? (string) $result['kode'] : '-';
                                $nama = is_array($result) && isset($result['nama']) ? (string) $result['nama'] : '-';
                                $dPlus = is_array($result) && isset($result['d_plus']) ? (float) $result['d_plus'] : 0;
                                $dMinus = is_array($result) && isset($result['d_minus']) ? (float) $result['d_minus'] : 0;
                                $preferensi = is_array($result) && isset($result['preferensi']) ? (float) $result['preferensi'] : 0;
                                ?>

                        <tr>
                            <td class="text-center">
                                <span class="badge bg-primary">#<?= esc($ranking) ?></span>
                            </td>
                            <td class="text-center"><?= esc($kode) ?></td>
                            <td><?= esc($nama) ?></td>
                            <td class="text-center"><?= number_format($dPlus, 4) ?></td>
                            <td class="text-center"><?= number_format($dMinus, 4) ?></td>
                            <td class="text-center fw-semibold"><?= number_format($preferensi, 4) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>