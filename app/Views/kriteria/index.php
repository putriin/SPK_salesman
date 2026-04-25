<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/kriteria.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="card shadow-sm border-0">
    <div
        class="card-header custom-header text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-light btn-sm rounded-3 px-2">
                ←
            </a>
            <h4 class="mb-0 fw-bold">CRITERIA DATA</h4>
        </div>

        <button class="btn btn-light text-dark fw-semibold rounded-3" id="btnAdd" type="button">
            Add Data
        </button>
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

        <div class="row g-3 align-items-end justify-content-between mb-3">
            <div class="col-md-3">
                <label for="entriesSelect" class="form-label">Show Entries</label>
                <select id="entriesSelect" class="form-select entries-select-custom">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                </select>
            </div>

            <div class="col-md-6 d-flex justify-content-end">
                <div class="search-group-custom">
                    <label for="searchInput" class="form-label">Search</label>
                    <input id="searchInput" type="text" class="form-control search-input-custom" placeholder="Search">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0" id="kriteriaTable">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Criteria Name</th>
                        <th style="width:180px;">Criteria Types</th>
                        <th style="width:180px;">Criteria Weight</th>
                        <th style="width:160px;">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
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
                <h5 class="modal-title fw-semibold" id="kriteriaModalLabel">Add Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="modalForm" method="post" action="<?= base_url('kriteria/save') ?>">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="formId" name="id" />

                    <div class="mb-3">
                        <label for="formNama" class="form-label">Criteria Name</label>
                        <input type="text" id="formNama" name="nama_kriteria" class="form-control"
                            placeholder="Criteria name" required>
                    </div>

                    <div class="mb-3">
                        <label for="formJenis" class="form-label">Criteria Types</label>
                        <select id="formJenis" name="tipe" class="form-select" required>
                            <option value="">Choose type</option>
                            <option value="benefit">Benefit</option>
                            <option value="cost">Cost</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="formBobot" class="form-label">Criteria Weight</label>
                        <input type="text" id="formBobot" name="bobot" class="form-control"
                            placeholder="Contoh: 0,226195029 atau 0.226195029" inputmode="decimal" required>
                        <small class="text-muted d-block mt-2">
                            Gunakan bobot desimal, contoh: 0,226195029
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-cancel-custom" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save</button>
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
                <h5 class="modal-title fw-semibold" id="deleteKriteriaModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p id="deleteModalText" class="mb-0">
                    Yakin ingin menghapus data ini?
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-custom" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-delete-custom" id="deleteConfirmBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
window.__KRITERIA_ROWS__ = <?= json_encode($rows ?? []) ?>;
window.__KRITERIA_DELETE_URL__ = <?= json_encode(base_url('kriteria/delete')) ?>;

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
            if (!formBobot) return;

            const rawValue = formBobot.value.trim();

            if (rawValue === '') {
                alert('Bobot kriteria wajib diisi.');
                e.preventDefault();
                return;
            }

            const normalized = rawValue.replace(',', '.');

            if (isNaN(normalized)) {
                alert('Bobot harus berupa angka yang valid.');
                e.preventDefault();
                return;
            }

            formBobot.value = normalized;
        });
    }
});
</script>
<script src="<?= base_url('assets/js/kriteria.js') ?>"></script>

<?= $this->endSection() ?>