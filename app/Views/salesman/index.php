<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/salesman.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$salesmanRows = is_array($rows ?? null) ? $rows : [];
?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-bottom">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">←</a>
                <h5 class="mb-0 fw-semibold text-dark">Alternatif Data</h5>
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
                <input id="searchInput" class="form-control" type="text" placeholder="Search...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" id="salesmanTable">

                <thead class="table-light text-center">
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th style="width: 80px;">ID</th>
                        <th style="width: 120px;">Kode</th>

                        <th style="width: 180px;">Nama</th>

                        <th style="width: 100px;">Gender</th>

                        <th style="width: 180px;">Alamat</th>

                        <th style="width: 200px;">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    <?php if (!empty($salesmanRows)): ?>
                    <?php foreach ($salesmanRows as $index => $row): ?>

                    <?php
                    $id = isset($row['id']) ? (int)$row['id'] : 0;
                    $kode = isset($row['kode']) ? (string)$row['kode'] : '-';
                    $nama = isset($row['nama']) ? (string)$row['nama'] : '-';
                    $gender = isset($row['gender']) ? (string)$row['gender'] : '-';
                    $alamat = isset($row['alamat']) ? (string)$row['alamat'] : '-';
                    ?>

                    <tr>
                        <td class="text-center"><?= esc((string) ($index + 1)) ?></td>
                        <td class="text-center"><?= esc((string) $id) ?></td>
                        <td class="text-center"><?= esc($kode) ?></td>

                        <td class="text-center fw-medium">
                            <?= esc($nama) ?>
                        </td>

                        <td class="text-center"><?= esc($gender) ?></td>

                        <td class="text-center">
                            <?= esc($alamat) ?>
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-warning btn-sm text-white px-3">Edit</button>
                                <button class="btn btn-danger btn-sm px-3">Hapus</button>
                            </div>
                        </td>
                    </tr>

                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            Tidak ada data
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
</div>

<form id="deleteForm" method="post" style="display:none;">
    <?= csrf_field() ?>
</form>

<div class="modal fade" id="salesmanModal" tabindex="-1" aria-labelledby="salesmanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="salesmanModalLabel">Data Salesman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="modalForm" method="post" action="<?= base_url('salesman/save') ?>">
                <div class="modal-body">
                    <?= csrf_field() ?>

                    <input type="hidden" id="formId" name="id">

                    <div class="mb-3">
                        <label for="formKode" class="form-label">Kode Alternatif</label>
                        <input type="text" id="formKode" name="kode_alternatif" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="formNama" class="form-label">Nama</label>
                        <input type="text" id="formNama" name="nama" class="form-control" required>
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
                        <label for="formAlamat" class="form-label">Alamat</label>
                        <input type="text" id="formAlamat" name="alamat" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-primary" type="submit">
                        Simpan
                    </button>
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
                <h5 class="modal-title fw-semibold text-danger" id="deleteSalesmanModalLabel">
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
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
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
window.__SALESMAN_ROWS__ =
    <?= json_encode($salesmanRows, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
window.__SALESMAN_DELETE_URL__ =
    <?= json_encode(base_url('salesman/delete'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
</script>

<script src="<?= base_url('assets/js/salesman.js') ?>"></script>

<?= $this->endSection() ?>