<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/penilaian.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="card shadow-sm border-0">
    <div
        class="card-header custom-header text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-light btn-sm rounded-3 px-2">
                ←
            </a>
            <h4 class="mb-0 fw-bold">SALESMAN ASSESSMENT</h4>
        </div>
    </div>

    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
        <?php endif; ?>

        <form id="assessmentForm" method="post" action="<?= base_url('penilaian/save') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="edit_mode" value="<?= !empty($editMode) ? '1' : '0' ?>">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="periode" class="form-label">Periode</label>
                    <input type="month" id="periode" name="periode" class="form-control"
                        value="<?= esc($formData['periode'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="salesman" class="form-label">Salesman</label>
                    <select id="salesman" name="salesman" class="form-select" required>
                        <option value="">Choose salesman</option>
                        <?php foreach (($salesmen ?? []) as $s): ?>
                        <option value="<?= esc($s['id']) ?>"
                            <?= (($formData['salesman_id'] ?? '') == $s['id']) ? 'selected' : '' ?>>
                            <?= esc($s['nama']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-1 fw-semibold">Input Nilai Kriteria</h5>
                    <p class="mb-0 text-muted small">Isi nilai untuk setiap kriteria yang tersedia.</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0">
                        <thead class="table-primary text-center">
                            <tr>
                                <th style="width:60px;">No</th>
                                <th>Criteria Name</th>
                                <th style="width:180px;">Criteria Type</th>
                                <th style="width:180px;">Weight</th>
                                <th style="width:180px;">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (($kriteria ?? []) as $i => $k): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td>
                                    <?= esc($k['nama']) ?>
                                    <input type="hidden" name="kriteria_id[]" value="<?= esc($k['id']) ?>">
                                </td>
                                <td class="text-center"><?= esc($k['jenis']) ?></td>
                                <td class="text-center"><?= esc($k['bobot']) ?></td>
                                <td>
                                    <input type="number" name="nilai[]" class="form-control score-input" min="0"
                                        max="100" placeholder="0 - 100"
                                        value="<?= esc($formData['nilai'][$k['id']] ?? '') ?>" required>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                            <?php if (empty($kriteria)): ?>
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
                <a href="<?= base_url('penilaian') ?>" class="btn btn-cancel-custom">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?= !empty($editMode) ? 'Update Assessment' : 'Save Assessment' ?>
                </button>
            </div>
        </form>
    </div>
</section>

<section class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-1 fw-semibold">Rekap Data Penilaian</h5>
        <p class="mb-0 text-muted small">Data penilaian per salesman yang sudah disimpan.</p>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Periode</th>
                        <th>Salesman</th>
                        <th style="width:160px;">Total Kriteria</th>
                        <th style="width:160px;">Status</th>
                        <th style="width:220px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rekap)): ?>
                    <?php foreach ($rekap as $i => $r): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td class="text-center"><?= esc($r['periode']) ?></td>
                        <td><?= esc($r['salesman']) ?></td>
                        <td class="text-center"><?= esc($r['total_kriteria']) ?></td>
                        <td class="text-center">
                            <?php if (($r['status'] ?? '') === 'Lengkap'): ?>
                            <span class="badge bg-success">Lengkap</span>
                            <?php else: ?>
                            <span class="badge bg-warning text-dark">Belum Lengkap</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-2 flex-wrap justify-content-center">
                                <a class="btn btn-sm btn-edit-custom"
                                    href="<?= base_url('penilaian?edit=1&salesman_id=' . $r['salesman_id'] . '&periode=' . urlencode($r['periode'])) ?>">
                                    Edit
                                </a>

                                <a class="btn btn-sm btn-detail-custom"
                                    href="<?= base_url('penilaian/detail/' . $r['salesman_id'] . '/' . $r['periode']) ?>">
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Belum ada data penilaian.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-1 fw-semibold">Proses TOPSIS per Periode</h5>
        <p class="mb-0 text-muted small">Riwayat dan detail perhitungan kini dilihat dari menu Proses Perhitungan.</p>
    </div>

    <div class="card-body">
        <div class="alert alert-primary mb-0">
            <strong>Info:</strong> Setelah data penilaian disimpan atau diedit, buka menu
            <strong>Proses Perhitungan</strong> untuk memilih periode, melihat hasil tersimpan, atau memproses ulang
            hasil TOPSIS.
        </div>
    </div>
</section>

<?= $this->endSection() ?>