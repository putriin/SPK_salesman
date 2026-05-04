<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/penilaian.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$salesmenRows = isset($salesmen) && is_array($salesmen) ? $salesmen : [];
$kriteriaRows = isset($kriteria) && is_array($kriteria) ? $kriteria : [];
$rekapRows = isset($rekap) && is_array($rekap) ? $rekap : [];
$formValues = isset($formData) && is_array($formData) ? $formData : [];
$nilaiValues = isset($formValues['nilai']) && is_array($formValues['nilai']) ? $formValues['nilai'] : [];
$isEditMode = isset($editMode) && !empty($editMode);
?>

<section class="card border-0 shadow-sm">
    <div class="card-header bg-light border-bottom">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">←</a>
            <h5 class="mb-0 fw-semibold text-dark">Penilaian Kinerja</h5>
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

        <form id="assessmentForm" method="post" action="<?= base_url('penilaian/save') ?>">
            <?= csrf_field() ?>

            <input type="hidden" name="edit_mode" value="<?= $isEditMode ? '1' : '0' ?>">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="periode" class="form-label">Periode</label>
                    <input type="month" id="periode" name="periode" class="form-control"
                        value="<?= esc((string) ($formValues['periode'] ?? '')) ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="salesman" class="form-label">Salesman</label>
                    <select id="salesman" name="salesman" class="form-select" required>
                        <option value="">Pilih salesman</option>

                        <?php foreach ($salesmenRows as $salesman): ?>
                        <?php
                            $salesmanId = is_array($salesman) && isset($salesman['id']) ? (string) $salesman['id'] : '';
                            $salesmanNama = is_array($salesman) && isset($salesman['nama']) ? (string) $salesman['nama'] : '-';
                            $selectedSalesmanId = (string) ($formValues['salesman_id'] ?? '');
                            ?>

                        <option value="<?= esc($salesmanId) ?>"
                            <?= $selectedSalesmanId === $salesmanId ? 'selected' : '' ?>>
                            <?= esc($salesmanNama) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-1 fw-semibold">Input Nilai Kriteria</h5>
                    <p class="mb-0 text-muted small">Isi nilai kinerja salesman berdasarkan setiap kriteria yang
                        tersedia.</p>
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
                            <?php if (!empty($kriteriaRows)): ?>
                            <?php foreach ($kriteriaRows as $index => $item): ?>
                            <?php
                                    $kriteriaId = is_array($item) && isset($item['id']) ? (string) $item['id'] : '';
                                    $kriteriaNama = is_array($item) && isset($item['nama']) ? (string) $item['nama'] : '-';
                                    $kriteriaJenis = is_array($item) && isset($item['jenis']) ? (string) $item['jenis'] : '-';
                                    $kriteriaBobot = is_array($item) && isset($item['bobot']) ? (string) $item['bobot'] : '-';
                                    $nilai = (string) ($nilaiValues[$kriteriaId] ?? '');
                                    ?>

                            <tr>
                                <td class="text-center"><?= esc((string) ($index + 1)) ?></td>

                                <td class="text-start ps-3 fw-medium">
                                    <?= esc($kriteriaNama) ?>
                                    <input type="hidden" name="kriteria_id[]" value="<?= esc($kriteriaId) ?>">
                                </td>

                                <td class="text-center"><?= esc(ucfirst($kriteriaJenis)) ?></td>

                                <td class="text-center"><?= esc($kriteriaBobot) ?></td>

                                <td>
                                    <input type="number" name="nilai[]" class="form-control text-center score-input"
                                        min="0" max="100" placeholder="0 - 100" value="<?= esc($nilai) ?>" required>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Belum ada data kriteria.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
                <a href="<?= base_url('penilaian') ?>" class="btn btn-outline-secondary">Cancel</a>

                <button type="submit" class="btn btn-primary">
                    <?= $isEditMode ? 'Update Penilaian' : 'Simpan Penilaian' ?>
                </button>
            </div>
        </form>
    </div>
</section>

<section class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-light border-bottom">
        <h5 class="mb-1 fw-semibold">Rekap Penilaian Kinerja</h5>
        <p class="mb-0 text-muted small">Rekap hasil input penilaian kinerja per salesman yang sudah disimpan.</p>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width: 70px;">No</th>
                        <th style="width: 160px;">Periode</th>
                        <th>Salesman</th>
                        <th style="width: 160px;">Total Kriteria</th>
                        <th style="width: 160px;">Status</th>
                        <th style="width: 220px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($rekapRows)): ?>
                    <?php foreach ($rekapRows as $index => $row): ?>
                    <?php
                            $periode = is_array($row) && isset($row['periode']) ? (string) $row['periode'] : '';
                            $salesmanNama = is_array($row) && isset($row['salesman']) ? (string) $row['salesman'] : '-';
                            $salesmanId = is_array($row) && isset($row['salesman_id']) ? (string) $row['salesman_id'] : '';
                            $totalKriteria = is_array($row) && isset($row['total_kriteria']) ? (string) $row['total_kriteria'] : '0';
                            $status = is_array($row) && isset($row['status']) ? (string) $row['status'] : '-';
                            ?>

                    <tr>
                        <td class="text-center"><?= esc((string) ($index + 1)) ?></td>
                        <td class="text-center"><?= esc($periode) ?></td>
                        <td class="text-center fw-medium"><?= esc($salesmanNama) ?></td>
                        <td class="text-center"><?= esc($totalKriteria) ?></td>

                        <td class="text-center">
                            <?php if ($status === 'Lengkap'): ?>
                            <span class="badge bg-success">Lengkap</span>
                            <?php else: ?>
                            <span class="badge bg-warning text-dark">Belum Lengkap</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <div class="d-inline-flex gap-2 flex-wrap justify-content-center">
                                <a class="btn btn-sm btn-warning text-white px-3"
                                    href="<?= base_url('penilaian?edit=1&salesman_id=' . urlencode($salesmanId) . '&periode=' . urlencode($periode)) ?>">
                                    Edit
                                </a>

                                <a class="btn btn-sm btn-primary px-3"
                                    href="<?= base_url('penilaian/detail/' . urlencode($salesmanId) . '/' . urlencode($periode)) ?>">
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Belum ada penilaian kinerja.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-light border-bottom">
        <h5 class="mb-1 fw-semibold">Proses TOPSIS per Periode</h5>
        <p class="mb-0 text-muted small">Riwayat dan detail perhitungan dilihat dari menu Proses Perhitungan.</p>
    </div>

    <div class="card-body">
        <div class="alert alert-primary mb-0">
            <strong>Info:</strong> Setelah penilaian kinerja disimpan atau diedit, buka menu
            <strong>Proses Perhitungan</strong> untuk memilih periode, melihat hasil tersimpan, atau memproses ulang
            hasil TOPSIS.
        </div>
    </div>
</section>

<?= $this->endSection() ?>