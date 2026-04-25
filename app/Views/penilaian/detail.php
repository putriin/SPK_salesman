<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/penilaian.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="card shadow-sm border-0">
    <div
        class="card-header custom-header text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('penilaian') ?>" class="btn btn-light btn-sm rounded-3 px-2">
                ←
            </a>
            <h4 class="mb-0 fw-bold">DETAIL PENILAIAN</h4>
        </div>
    </div>

    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-3">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Periode</div>
                        <div class="fw-semibold"><?= esc($detail['periode'] ?? '-') ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Salesman</div>
                        <div class="fw-semibold"><?= esc($detail['salesman'] ?? '-') ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total Kriteria</div>
                        <div class="fw-semibold"><?= count($detail['nilai'] ?? []) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small mb-1">Status</div>
                        <div>
                            <span class="badge bg-success">Lengkap</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-1 fw-semibold">Detail Nilai Kriteria</h5>
                <p class="mb-0 text-muted small">Berikut rincian nilai salesman terhadap setiap kriteria.</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width:60px;">No</th>
                            <th>Criteria Name</th>
                            <th style="width:160px;">Criteria Type</th>
                            <th style="width:160px;">Weight</th>
                            <th style="width:160px;">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($detail['nilai'])): ?>
                        <?php foreach ($detail['nilai'] as $i => $n): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td><?= esc($n['kriteria']) ?></td>
                            <td class="text-center"><?= esc($n['jenis']) ?></td>
                            <td class="text-center"><?= esc($n['bobot']) ?></td>
                            <td class="text-center"><?= esc($n['score']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada detail penilaian.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
            <a href="<?= base_url('penilaian') ?>" class="btn btn-cancel-custom">Kembali</a>

            <a href="<?= base_url('penilaian?edit=1&salesman_id=' . $detail['salesman_id'] . '&periode=' . urlencode($detail['periode'])) ?>"
                class="btn btn-edit-custom">
                Edit Penilaian
            </a>

            <a href="<?= base_url('perhitungan?periode=' . urlencode($detail['periode'] ?? '')) ?>"
                class="btn btn-primary">
                Proses Perhitungan
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>