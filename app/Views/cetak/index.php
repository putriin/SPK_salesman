<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/cetak.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="card shadow-sm border-0 mb-4 no-print">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">

            <!-- LEFT -->
            <form method="get" action="<?= base_url('cetak') ?>" class="d-flex gap-2 flex-wrap align-items-end">
                <div>
                    <label for="periode" class="form-label mb-1">Pilih Periode</label>
                    <select name="periode" id="periode" class="form-select">
                        <?php foreach ($periodeOptions as $p): ?>
                        <option value="<?= esc($p) ?>" <?= $periode === $p ? 'selected' : '' ?>>
                            <?= esc($p) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary px-4">
                        Tampilkan
                    </button>
                </div>
            </form>

            <!-- RIGHT -->
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= base_url('cetak/download-pdf?periode=' . urlencode($periode)) ?>"
                    class="btn btn-outline-primary">
                    Download PDF
                </a>

                <button type="button" class="btn btn-primary" onclick="window.print()">
                    Print
                </button>
            </div>

        </div>
    </div>
</section>

<section class="card shadow-sm border-0 print-area">
    <div class="card-body">

        <!-- HEADER -->
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">LAPORAN HASIL PERHITUNGAN TOPSIS</h4>
            <div class="text-muted">Sistem Pendukung Keputusan Pemilihan Salesman Terbaik</div>
            <div class="mt-2"><strong>Periode:</strong> <?= esc($periode) ?></div>
        </div>

        <!-- WINNER -->
        <?php if (!empty($winner)): ?>
        <div class="alert alert-success">
            <strong>Salesman Terbaik:</strong>
            <?= esc($winner['nama']) ?> (<?= esc($winner['kode']) ?>)
            dengan nilai preferensi
            <strong><?= number_format($winner['preferensi'], 4) ?></strong>.
        </div>
        <?php endif; ?>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width:100px;">Ranking</th>
                        <th style="width:120px;">Kode</th>
                        <th>Nama Salesman</th>
                        <th style="width:140px;">D+</th>
                        <th style="width:140px;">D-</th>
                        <th style="width:180px;">Nilai Preferensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($results)): ?>
                    <?php foreach ($results as $row): ?>
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-primary">#<?= esc($row['ranking']) ?></span>
                        </td>
                        <td class="text-center"><?= esc($row['kode']) ?></td>
                        <td><?= esc($row['nama']) ?></td>
                        <td class="text-center"><?= number_format($row['d_plus'], 4) ?></td>
                        <td class="text-center"><?= number_format($row['d_minus'], 4) ?></td>
                        <td class="text-center fw-bold"><?= number_format($row['preferensi'], 4) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Belum ada hasil perhitungan untuk periode ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- SIGNATURE -->
        <div class="d-flex justify-content-end mt-5">
            <div class="text-center">
                <div>Mengetahui,</div>
                <div style="height:70px;"></div>
                <div class="fw-semibold">Manajer</div>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>