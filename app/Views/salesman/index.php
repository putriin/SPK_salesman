<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/salesman.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm border-0">
    <div
        class="card-header custom-header text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-light btn-sm rounded-3 px-2">
                ←
            </a>
            <h4 class="mb-0 fw-bold">ALTERNATIF DATA</h4>
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
                    <option value="5" selected>5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                </select>
            </div>

            <div class="col-md-6 d-flex justify-content-end">
                <div class="search-group-custom">
                    <label for="searchInput" class="form-label">Search</label>
                    <input id="searchInput" class="form-control search-input-custom" type="text" placeholder="Search" />
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0" id="salesmanTable">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th style="width:90px;">ID</th>
                        <th style="width:160px;">Alternatif Code</th>
                        <th>Name</th>
                        <th style="width:130px;">Gender</th>
                        <th>Address</th>
                        <th style="width:160px;">Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach (($rows ?? []) as $i => $r): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td class="text-center"><?= esc($r['id']) ?></td>
                        <td class="text-center"><?= esc($r['kode']) ?></td>
                        <td><?= esc($r['nama']) ?></td>
                        <td class="text-center"><?= esc($r['gender']) ?></td>
                        <td><?= esc($r['alamat']) ?></td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-2">
                                <button class="btn btn-sm btn-edit-custom" type="button" data-action="edit"
                                    data-id="<?= esc($r['id']) ?>">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-delete-custom" type="button" data-action="delete"
                                    data-id="<?= esc($r['id']) ?>">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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
</div>

<form id="deleteForm" method="post" style="display:none;">
    <?= csrf_field() ?>
</form>

<div class="modal fade" id="salesmanModal" tabindex="-1" aria-labelledby="salesmanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="salesmanModalLabel">Add Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="modalForm" method="post" action="<?= base_url('salesman/save') ?>">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="formId" name="id" />

                    <div class="mb-3">
                        <label for="formKode" class="form-label">Alternatif Code</label>
                        <input type="text" id="formKode" name="kode_alternatif" class="form-control" placeholder="A08"
                            required />
                    </div>

                    <div class="mb-3">
                        <label for="formNama" class="form-label">Name</label>
                        <input type="text" id="formNama" name="nama" class="form-control" placeholder="Nama" required />
                    </div>

                    <div class="mb-3">
                        <label for="formGender" class="form-label">Gender</label>
                        <select id="formGender" name="gender" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="L">L</option>
                            <option value="P">P</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="formAlamat" class="form-label">Address</label>
                        <input type="text" id="formAlamat" name="alamat" class="form-control" placeholder="Alamat"
                            required />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel-custom" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSalesmanModal" tabindex="-1" aria-labelledby="deleteSalesmanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="deleteSalesmanModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="deleteModalText" class="mb-0">Yakin ingin menghapus data ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-custom" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-delete-custom" id="deleteConfirmBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
window.__SALESMAN_ROWS__ = <?= json_encode($rows ?? []) ?>;
window.__SALESMAN_DELETE_URL__ = <?= json_encode(base_url('salesman/delete')) ?>;
</script>
<script src="<?= base_url('assets/js/salesman.js') ?>"></script>

<?= $this->endSection() ?>