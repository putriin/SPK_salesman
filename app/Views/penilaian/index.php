<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
@media (max-width:768px) {

    table.table th,
    table.table td {
        font-size: 0.85rem;
        padding: 0.35rem;
    }

    .d-flex.gap-2>a {
        flex: 1 1 auto;
        font-size: 0.75rem;
        padding: 0.35rem 0.5rem;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="card border-0 shadow-sm">
    <div class="card-header bg-light border-bottom d-flex align-items-center gap-2">
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">←</a>
        <h5 class="mb-0 fw-semibold text-dark">Penilaian Kinerja</h5>
    </div>

    <div class="card-body">
        <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('penilaian/save') ?>">
            <?= csrf_field() ?>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="periode" class="form-label">Periode</label>
                    <input type="month" id="periode" name="periode" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="salesman" class="form-label">Salesman</label>
                    <select id="salesman" name="salesman" class="form-select" required>
                        <option value="">Pilih salesman</option>
                        <?php foreach($salesmen as $s): ?>
                        <option value="<?= esc($s['id']) ?>"><?= esc($s['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Input Nilai Kriteria -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-1 fw-semibold">Input Nilai Kriteria</h5>
                    <p class="mb-0 text-muted small">Isi nilai kinerja salesman untuk setiap kriteria.</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width:60px;">No</th>
                                <th style="width:40%;">Nama Kriteria</th>
                                <th style="width:15%;">Tipe</th>
                                <th style="width:15%;">Bobot</th>
                                <th style="width:30%;">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($kriteria)): ?>
                            <?php foreach($kriteria as $i=>$k): ?>
                            <tr>
                                <td class="text-center"><?= $i+1 ?></td>
                                <td class="text-start ps-3 fw-medium"><?= esc($k['nama']) ?>
                                    <input type="hidden" name="kriteria_id[]" value="<?= esc($k['id']) ?>">
                                </td>
                                <td class="text-center"><?= esc($k['jenis']) ?></td>
                                <td class="text-center"><?= number_format($k['bobot'], 0) ?></td>
                                <td><input type="number" name="nilai[]" class="form-control text-center" min="0"
                                        max="100" required></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada kriteria.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-2 mt-4">
                <a href="<?= base_url('penilaian') ?>" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
            </div>
        </form>
    </div>
</section>

<!-- Rekap Penilaian -->
<section class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-light border-bottom">
        <h5 class="mb-1 fw-semibold">Rekap Penilaian Kinerja</h5>
        <p class="mb-0 text-muted small">Rekap hasil input per salesman.</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:60px;">No</th>
                        <th style="width:140px;">Periode</th>
                        <th style="width:240px;">Salesman</th>
                        <th style="width:120px;">Total Kriteria</th>
                        <th style="width:120px;">Status</th>
                        <th style="width:300px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // total kriteria master
                    $totalKriteriaMaster = count($kriteria);
                    ?>
                    <?php if(!empty($rekap)): ?>
                    <?php foreach($rekap as $i=>$r): ?>
                    <?php 
                        $status = ($r['total_kriteria'] == $totalKriteriaMaster) ? 'Lengkap' : 'Belum Lengkap';
                        ?>
                    <tr>
                        <td class="text-center"><?= $i+1 ?></td>
                        <td class="text-center"><?= esc($r['periode']) ?></td>
                        <td class="text-center fw-medium"><?= esc($r['salesman']) ?></td>
                        <td class="text-center"><?= esc($r['total_kriteria']) ?></td>
                        <td class="text-center">
                            <?php if($status==='Lengkap'): ?>
                            <span class="badge bg-success">Lengkap</span>
                            <?php else: ?>
                            <span class="badge bg-warning text-dark">Belum Lengkap</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <a class="btn btn-sm btn-warning text-white px-3"
                                    href="<?= base_url('penilaian?edit=1&salesman_id='.$r['salesman_id'].'&periode='.$r['periode']) ?>">Edit</a>
                                <a class="btn btn-sm btn-primary px-3"
                                    href="<?= base_url('penilaian/detail/'.$r['salesman_id'].'/'.$r['periode']) ?>">Detail</a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger px-3 btn-hapus"
                                    data-salesman="<?= esc($r['salesman_id']) ?>"
                                    data-periode="<?= esc($r['periode']) ?>">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada penilaian kinerja.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', () => {
        const sid = btn.dataset.salesman;
        const periode = btn.dataset.periode;
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Yakin ingin menghapus penilaian salesman ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = `<?= base_url('penilaian/delete') ?>/${sid}/${periode}`;
            }
        });
    });
});
</script>

<?= $this->endSection() ?>