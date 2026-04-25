<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/perhitungan.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$validAlternativeCount = $validAlternativeCount ?? count($alternatives ?? []);
$criteriaCount = count($criteria ?? []);
$hasCriteria = $criteriaCount > 0;
$hasEnoughAlternatives = $validAlternativeCount >= 2;
$hasResults = !empty($results);
?>

<section class="card shadow-sm border-0">
    <div
        class="card-header custom-header text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-light btn-sm rounded-3 px-2">
                ←
            </a>
            <h4 class="mb-0 fw-bold">PROSES PERHITUNGAN TOPSIS</h4>
        </div>
    </div>

    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="periode" class="form-label fw-semibold">Pilih Periode</label>
                        <form method="get" action="<?= base_url('perhitungan') ?>" class="d-flex gap-2">
                            <select name="periode" id="periode" class="form-select">
                                <?php if (!empty($periodOptions ?? [])): ?>
                                <?php foreach (($periodOptions ?? []) as $option): ?>
                                <option value="<?= esc($option) ?>"
                                    <?= ($selectedPeriode ?? '') === $option ? 'selected' : '' ?>>
                                    <?= esc($option) ?>
                                </option>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <option value="">Belum ada periode</option>
                                <?php endif; ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Tampilkan</button>
                        </form>
                    </div>

                    <div class="col-md-8">
                        <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                            <?php if (!empty($selectedPeriode) && !empty($periodOptions ?? [])): ?>
                            <form method="post" action="<?= base_url('perhitungan/process') ?>"
                                onsubmit="return confirm('Perhitungan TOPSIS untuk periode <?= esc($selectedPeriode) ?> akan diproses ulang sesuai data penilaian terbaru. Lanjutkan?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="periode" value="<?= esc($selectedPeriode) ?>">
                                <button type="submit" class="btn btn-success"
                                    <?= empty($canProcess) ? 'disabled' : '' ?>>
                                    <?= !empty($hasSnapshot) ? 'Proses Ulang / Update Hasil' : 'Proses TOPSIS' ?>
                                </button>
                            </form>
                            <?php endif; ?>

                            <?php if (!empty($hasSnapshot)): ?>
                            <a href="<?= base_url('cetak?periode=' . urlencode($selectedPeriode)) ?>"
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

        <?php if (!empty($hasSnapshot) && !empty($lastCalculatedAt)): ?>
        <div class="alert alert-secondary mb-4">
            Hasil yang sedang ditampilkan adalah hasil tersimpan untuk periode
            <strong><?= esc($selectedPeriode) ?></strong>.
            Terakhir dihitung pada <strong><?= esc($lastCalculatedAt) ?></strong>.
        </div>
        <?php endif; ?>

        <?php if (!empty($isStale)): ?>
        <div class="alert alert-warning mb-4">
            Data penilaian periode <strong><?= esc($selectedPeriode) ?></strong> sudah berubah setelah perhitungan
            terakhir.
            Klik <strong>Proses Ulang / Update Hasil</strong> untuk memperbarui hasil dan riwayat perhitungan.
        </div>
        <?php endif; ?>

        <?php if (empty($hasSnapshot)): ?>
        <div class="alert alert-info mb-4">
            Belum ada hasil perhitungan tersimpan untuk periode <strong><?= esc($selectedPeriode) ?></strong>.
            <?php if (!empty($canProcess)): ?>
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
                        <div class="fw-semibold"><?= esc($selectedPeriode) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Salesman Lengkap Saat Ini</div>
                        <div class="fw-semibold"><?= esc($currentCompleteCount ?? 0) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Jumlah Kriteria Saat Ini</div>
                        <div class="fw-semibold"><?= esc($currentCriteriaCount ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($winner)): ?>
        <div class="alert alert-success mb-4">
            <h5 class="fw-semibold mb-2">Hasil Utama</h5>
            <p class="mb-0">
                Salesman terbaik periode <strong><?= esc($selectedPeriode) ?></strong> adalah
                <strong><?= esc($winner['nama']) ?></strong>
                dengan nilai preferensi
                <strong><?= number_format($winner['preferensi'], 4) ?></strong>.
            </p>
        </div>
        <?php endif; ?>

        <?php if (!empty($incompleteAlternatives)): ?>
        <div class="alert alert-warning mb-4">
            <h5 class="fw-semibold mb-2">Perhatian</h5>
            <p class="mb-2">
                Salesman berikut belum ikut dihitung karena data penilaiannya pada periode ini belum lengkap untuk semua
                kriteria:
            </p>
            <ul class="mb-0">
                <?php foreach ($incompleteAlternatives as $item): ?>
                <li><?= esc($item['kode']) ?> - <?= esc($item['nama']) ?></li>
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
                pada periode <strong><?= esc($selectedPeriode) ?></strong> baru berjumlah
                <strong><?= esc($currentCompleteCount ?? 0) ?></strong>.
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
                            <?php foreach ($criteria as $criterion): ?>
                            <th><?= esc($criterion['kode']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alternatives as $alt): ?>
                        <tr>
                            <td class="text-center"><?= esc($alt['kode']) ?></td>
                            <td><?= esc($alt['nama']) ?></td>
                            <?php foreach (($alt['scores'] ?? []) as $score): ?>
                            <td class="text-center"><?= esc($score) ?></td>
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
                        <?php foreach ($criteria as $i => $criterion): ?>
                        <tr>
                            <td class="text-center"><?= esc($criterion['kode']) ?></td>
                            <td><?= esc($criterion['nama']) ?></td>
                            <td class="text-center"><?= esc(ucfirst($criterion['tipe'])) ?></td>
                            <td class="text-center"><?= esc($criterion['bobot']) ?></td>
                            <td class="text-center"><?= number_format($weights[$i] ?? 0, 4) ?></td>
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
                            <?php foreach ($criteria as $criterion): ?>
                            <th><?= esc($criterion['kode']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alternatives as $rowIndex => $alt): ?>
                        <tr>
                            <td class="text-center"><?= esc($alt['kode']) ?></td>
                            <td><?= esc($alt['nama']) ?></td>
                            <?php foreach (($normalized[$rowIndex] ?? []) as $value): ?>
                            <td class="text-center"><?= number_format($value, 4) ?></td>
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
                            <?php foreach ($criteria as $criterion): ?>
                            <th><?= esc($criterion['kode']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alternatives as $rowIndex => $alt): ?>
                        <tr>
                            <td class="text-center"><?= esc($alt['kode']) ?></td>
                            <td><?= esc($alt['nama']) ?></td>
                            <?php foreach (($weighted[$rowIndex] ?? []) as $value): ?>
                            <td class="text-center"><?= number_format($value, 4) ?></td>
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
                            <?php foreach ($criteria as $criterion): ?>
                            <th><?= esc($criterion['kode']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center fw-semibold">A+</td>
                            <?php foreach (($idealPositive ?? []) as $value): ?>
                            <td class="text-center"><?= number_format($value, 4) ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td class="text-center fw-semibold">A-</td>
                            <?php foreach (($idealNegative ?? []) as $value): ?>
                            <td class="text-center"><?= number_format($value, 4) ?></td>
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
                        <?php foreach ($results as $result): ?>
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-primary">#<?= esc($result['ranking']) ?></span>
                            </td>
                            <td class="text-center"><?= esc($result['kode']) ?></td>
                            <td><?= esc($result['nama']) ?></td>
                            <td class="text-center"><?= number_format($result['d_plus'], 4) ?></td>
                            <td class="text-center"><?= number_format($result['d_minus'], 4) ?></td>
                            <td class="text-center fw-semibold"><?= number_format($result['preferensi'], 4) ?></td>
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