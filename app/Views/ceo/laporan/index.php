<?= $this->extend('layout/dashboard') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3">
            <form method="get" action="<?= base_url('ceo/laporan') ?>" class="d-flex flex-column">
                <label for="periode" class="form-label mb-2 fw-medium">Pilih Periode</label>
                <div class="d-flex gap-2 flex-wrap">
                    <select name="periode" id="periode" class="form-select" style="width: 140px;">
                        <?php foreach (($periodeOptions ?? []) as $option): ?>
                        <option value="<?= esc($option) ?>"
                            <?= (($selectedPeriode ?? '') === $option) ? 'selected' : '' ?>>
                            <?= esc($option) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-primary">
                        Tampilkan
                    </button>
                </div>
            </form>

            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= base_url('ceo/pdf?periode=' . urlencode($selectedPeriode ?? '')) ?>"
                    class="btn btn-outline-primary">
                    Download PDF
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    Print
                </button>
            </div>
        </div>
    </div>
</div>

<div id="print-area" class="card shadow-sm border-0">
    <div class="card-body p-4 p-lg-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-2">LAPORAN HASIL PERHITUNGAN TOPSIS</h2>
            <p class="mb-1 text-muted">Sistem Pendukung Keputusan Pemilihan Salesman Terbaik</p>
            <p class="mb-0"><strong>Periode:</strong> <?= esc($periode) ?></p>
        </div>

        <?php if (!empty($results)): ?>
        <?php $best = $results[0]; ?>
        <div class="alert alert-success border-success-subtle mb-4">
            <strong>Salesman Terbaik:</strong>
            <?= esc($best['nama']) ?> (<?= esc($best['kode']) ?>)
            dengan nilai preferensi
            <strong><?= esc(number_format((float) $best['preferensi'], 4)) ?></strong>.
        </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width: 90px;">Ranking</th>
                        <th style="width: 110px;">Kode</th>
                        <th>Nama Salesman</th>
                        <th style="width: 160px;">Nilai Preferensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($results)): ?>
                    <?php foreach ($results as $row): ?>
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-primary px-3 py-2">
                                #<?= esc($row['ranking']) ?>
                            </span>
                        </td>
                        <td class="text-center"><?= esc($row['kode']) ?></td>
                        <td><?= esc($row['nama']) ?></td>
                        <td class="text-center fw-semibold">
                            <?= esc(number_format((float) $row['preferensi'], 4)) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Belum ada data laporan pada periode ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-5 d-flex justify-content-end">
            <div class="text-center" style="min-width: 220px;">
                <p class="mb-5">Mengetahui,</p>
                <p class="fw-semibold mb-0">CEO</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {

    nav,
    .sidebar,
    .btn,
    form,
    .card:first-of-type {
        display: none !important;
    }

    body {
        background: #fff !important;
    }

    main.container-fluid,
    .container-fluid,
    .content-wrapper {
        padding: 0 !important;
        margin: 0 !important;
    }

    #print-area {
        border: none !important;
        box-shadow: none !important;
    }

    #print-area .card-body {
        padding: 0 !important;
    }
}
</style>
<?= $this->endSection() ?>