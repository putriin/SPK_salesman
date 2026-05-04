<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/penilaian.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$detailData = isset($detail) && is_array($detail) ? $detail : [];
$nilaiRows = isset($detailData['nilai']) && is_array($detailData['nilai']) ? $detailData['nilai'] : [];

$periode = isset($detailData['periode']) ? (string) $detailData['periode'] : '-';
$salesman = isset($detailData['salesman']) ? (string) $detailData['salesman'] : '-';
$salesmanId = isset($detailData['salesman_id']) ? (string) $detailData['salesman_id'] : '';
$totalKriteria = count($nilaiRows);
?>

<section class="card border-0 shadow-sm">
    <div class="card-header bg-light border-bottom">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('penilaian') ?>" class="btn btn-outline-secondary btn-sm">
                ←
            </a>

            <h5 class="mb-0 fw-semibold text-dark">
                Detail Penilaian Kinerja
            </h5>
        </div>
    </div>

    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-3">
            <?= esc((string) session()->getFlashdata('success')) ?>
        </div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Periode</div>
                        <div class="fw-semibold"><?= esc($periode) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Salesman</div>
                        <div class="fw-semibold"><?= esc($salesman) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total Kriteria</div>
                        <div class="fw-semibold"><?= esc((string) $totalKriteria) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Status</div>
                        <span class="badge bg-success">Lengkap</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-1 fw-semibold">Detail Nilai Kriteria</h5>
                <p class="mb-0 text-muted small">
                    Berikut rincian nilai kinerja salesman terhadap setiap kriteria.
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th style="width: 40%;">Nama Kriteria</th>
                            <th style="width: 15%;">Tipe</th>
                            <th style="width: 15%;">Bobot</th>
                            <th style="width: 30%;">Nilai</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($nilaiRows)): ?>
                        <?php foreach ($nilaiRows as $index => $row): ?>
                        <?php
                                $namaKriteria = is_array($row) && isset($row['kriteria']) ? (string) $row['kriteria'] : '-';
                                $jenis = is_array($row) && isset($row['jenis']) ? (string) $row['jenis'] : '-';
                                $bobot = is_array($row) && isset($row['bobot']) ? (string) $row['bobot'] : '-';
                                $score = is_array($row) && isset($row['score']) ? (string) $row['score'] : '-';
                                ?>

                        <tr>
                            <td class="text-center"><?= esc((string) ($index + 1)) ?></td>
                            <td class="text-start ps-3 fw-medium"><?= esc($namaKriteria) ?></td>
                            <td class="text-center"><?= esc(ucfirst($jenis)) ?></td>
                            <td class="text-center"><?= esc($bobot) ?></td>
                            <td class="text-center"><?= esc($score) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada detail penilaian kinerja.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
            <a href="<?= base_url('penilaian') ?>" class="btn btn-outline-secondary">
                Kembali
            </a>

            <a href="<?= base_url('penilaian?edit=1&salesman_id=' . urlencode($salesmanId) . '&periode=' . urlencode($periode)) ?>"
                class="btn btn-warning text-white">
                Edit Penilaian
            </a>

            <a href="<?= base_url('perhitungan?periode=' . urlencode($periode)) ?>" class="btn btn-primary">
                Proses Perhitungan
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>