<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/kriteria.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$kriteriaRows = is_array($rows ?? null) ? $rows : [];
?>

<section class="card border-0 shadow-sm">
    <div class="card-header bg-light border-bottom">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                    ←
                </a>

                <h5 class="mb-0 fw-semibold text-dark">
                    Data Kriteria
                </h5>
            </div>

            <button class="btn btn-primary btn-sm" id="btnAdd" type="button">
                + Tambah Data
            </button>
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

        <div class="row g-3 align-items-end justify-content-between mb-3">
            <div class="col-md-3">
                <label for="entriesSelect" class="form-label">Show Entries</label>
                <select id="entriesSelect" class="form-select">
                    <option value="5" selected>5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="searchInput" class="form-label">Search</label>
                <input id="searchInput" type="text" class="form-control" placeholder="Search...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" id="kriteriaTable">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width: 70px;">No</th>
                        <th>Nama Kriteria</th>
                        <th style="width: 180px;">Tipe</th>
                        <th style="width: 160px;">Bobot Kepentingan</th>
                        <th style="width: 190px;">Bobot Normalisasi</th>
                        <th style="width: 200px;">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    <?php if (!empty($kriteriaRows)): ?>
                    <?php foreach ($kriteriaRows as $index => $row): ?>
                    <?php
                        $id = $row['id'] ?? 0;
                        $namaKriteria = $row['nama_kriteria'] ?? '-';
                        $tipe = $row['tipe'] ?? '-';
                        $bobot = $row['bobot'] ?? '-';
                        $bobotNormalisasi = $row['bobot_normalisasi'] ?? '-';
                    ?>

                    <tr>
                        <td class="text-center"><?= esc((string) ($index + 1)) ?></td>
                        <td class="text-center fw-medium"><?= esc($namaKriteria) ?></td>
                        <td class="text-center"><?= esc(ucfirst($tipe)) ?></td>
                        <td class="text-center"><?= esc($bobot) ?></td>
                        <td class="text-center"><?= esc($bobotNormalisasi) ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-warning btn-sm text-white px-3" type="button" data-action="edit"
                                    data-id="<?= esc($id) ?>">
                                    Edit
                                </button>

                                <button class="btn btn-danger btn-sm px-3" type="button" data-action="delete"
                                    data-id="<?= esc($id) ?>">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada data kriteria.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
            <div class="text-muted small" id="tableInfo">Showing 0 of 0 entries</div>

            <div class="d-flex flex-wrap gap-2" id="pagination">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-page="prev">Previous</button>
                <button class="btn btn-primary btn-sm" type="button" data-page="1">1</button>
                <button class="btn btn-outline-secondary btn-sm" type="button" data-page="next">Next</button>
            </div>
        </div>
    </div>
</section>

<form id="deleteForm" method="post" style="display:none;">
    <?= csrf_field() ?>
</form>

<div class="modal fade" id="kriteriaModal" tabindex="-1" aria-labelledby="kriteriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="kriteriaModalLabel">Data Kriteria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="modalForm" method="post" action="<?= base_url('kriteria/save') ?>">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="formId" name="id">

                    <div class="mb-3">
                        <label for="formNama" class="form-label">Nama Kriteria</label>
                        <input type="text" id="formNama" name="nama_kriteria" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="formJenis" class="form-label">Tipe Kriteria</label>
                        <select id="formJenis" name="tipe" class="form-select" required>
                            <option value="">Pilih tipe</option>
                            <option value="benefit">Benefit</option>
                            <option value="cost">Cost</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="formBobot" class="form-label">Bobot Kepentingan</label>
                        <input type="text" id="formBobot" name="bobot" class="form-control"
                            placeholder="Contoh: 1 sampai 5" required>

                        <small class="text-muted d-block mt-2">
                            Isi bobot sesuai tingkat kepentingan, misalnya 1 = rendah sampai 5 = sangat penting.
                            Sistem akan otomatis menghitung bobot normalisasi.
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteKriteriaModal" tabindex="-1" aria-labelledby="deleteKriteriaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold text-danger" id="deleteKriteriaModalLabel">
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p id="deleteModalText" class="mb-0">
                    Yakin ingin menghapus data ini?
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button" class="btn btn-danger" id="deleteConfirmBtn">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window.__KRITERIA_ROWS__ =
    <?= json_encode($kriteriaRows, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
window.__KRITERIA_DELETE_URL__ =
    <?= json_encode(base_url('kriteria/delete'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

document.addEventListener('DOMContentLoaded', function() {
    const formBobot = document.getElementById('formBobot');
    const modalForm = document.getElementById('modalForm');

    if (formBobot) {
        formBobot.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9.,]/g, '');
        });
    }

    if (modalForm) {
        modalForm.addEventListener('submit', function(e) {
            if (!formBobot) {
                return;
            }

            const val = formBobot.value.trim().replace(',', '.');

            if (val === '' || isNaN(val) || Number(val) <= 0) {
                alert('Bobot harus berupa angka positif.');
                e.preventDefault();
                return;
            }

            formBobot.value = val;
        });
    }
});
</script>

<script src="<?= base_url('assets/js/kriteria.js') ?>"></script>

<?= $this->endSection() ?>