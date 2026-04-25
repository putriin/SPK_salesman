<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil TOPSIS</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #1f2937;
        margin: 28px 32px;
        line-height: 1.5;
    }

    .page-wrapper {
        width: 100%;
    }

    .report-header {
        text-align: center;
        margin-bottom: 24px;
        border-bottom: 2px solid #4a81b3;
        padding-bottom: 14px;
    }

    .report-kicker {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #4a81b3;
        margin-bottom: 6px;
    }

    .report-title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
    }

    .report-subtitle {
        margin: 4px 0 0;
        font-size: 12px;
        color: #6b7280;
    }

    .report-period {
        margin-top: 10px;
        font-size: 13px;
        color: #111827;
    }

    .info-box {
        margin: 18px 0 20px;
        padding: 12px 14px;
        background: #eef8f2;
        border: 1px solid #b7e4c7;
        border-left: 5px solid #39a96b;
        border-radius: 6px;
    }

    .info-box-title {
        font-weight: 700;
        color: #1f5135;
        margin-bottom: 4px;
    }

    .info-box-text {
        font-size: 12px;
        color: #1f2937;
    }

    .section-title {
        margin: 0 0 10px;
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
    }

    th,
    td {
        border: 1px solid #b8c7db;
        padding: 8px 10px;
        vertical-align: middle;
    }

    th {
        background: #dbe7f6;
        color: #1f2937;
        font-weight: 700;
        text-align: center;
    }

    td.center {
        text-align: center;
    }

    td.bold {
        font-weight: 700;
    }

    .rank-pill {
        display: inline-block;
        min-width: 34px;
        padding: 3px 8px;
        background: #1f6feb;
        color: #ffffff;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
    }

    .summary-table {
        width: 100%;
        margin-bottom: 18px;
        border-collapse: collapse;
    }

    .summary-table td {
        border: 1px solid #d6dee8;
        padding: 8px 10px;
    }

    .summary-label {
        width: 180px;
        background: #f8fafc;
        font-weight: 700;
    }

    .signature-wrap {
        width: 220px;
        margin-left: auto;
        margin-top: 44px;
        text-align: center;
    }

    .signature-space {
        height: 70px;
    }

    .footer-note {
        margin-top: 26px;
        font-size: 10px;
        color: #6b7280;
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="report-header">
            <div class="report-kicker">SPK TOPSIS</div>
            <h2 class="report-title">LAPORAN HASIL PERHITUNGAN TOPSIS</h2>
            <div class="report-subtitle">Sistem Pendukung Keputusan Pemilihan Salesman Terbaik</div>
            <div class="report-period"><strong>Periode:</strong> <?= esc($periode) ?></div>
        </div>

        <table class="summary-table">
            <tr>
                <td class="summary-label">Jenis Laporan</td>
                <td>Hasil perangkingan salesman berdasarkan metode TOPSIS</td>
            </tr>
            <tr>
                <td class="summary-label">Periode</td>
                <td><?= esc($periode) ?></td>
            </tr>
            <tr>
                <td class="summary-label">Jumlah Data Hasil</td>
                <td><?= count($results ?? []) ?> salesman</td>
            </tr>
        </table>

        <?php if (!empty($winner)): ?>
        <div class="info-box">
            <div class="info-box-title">Salesman Terbaik</div>
            <div class="info-box-text">
                <strong><?= esc($winner['nama']) ?></strong>
                (<?= esc($winner['kode']) ?>)
                memperoleh nilai preferensi tertinggi sebesar
                <strong><?= number_format($winner['preferensi'], 4) ?></strong>.
            </div>
        </div>
        <?php endif; ?>

        <h3 class="section-title">Tabel Hasil Perangkingan</h3>

        <table>
            <thead>
                <tr>
                    <th style="width: 90px;">Ranking</th>
                    <th style="width: 90px;">Kode</th>
                    <th>Nama Salesman</th>
                    <th style="width: 90px;">D+</th>
                    <th style="width: 90px;">D-</th>
                    <th style="width: 120px;">Nilai Preferensi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($results)): ?>
                <?php foreach ($results as $row): ?>
                <tr>
                    <td class="center">
                        <span class="rank-pill">#<?= esc($row['ranking']) ?></span>
                    </td>
                    <td class="center"><?= esc($row['kode']) ?></td>
                    <td><?= esc($row['nama']) ?></td>
                    <td class="center"><?= number_format($row['d_plus'], 4) ?></td>
                    <td class="center"><?= number_format($row['d_minus'], 4) ?></td>
                    <td class="center bold"><?= number_format($row['preferensi'], 4) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6" class="center">Belum ada hasil perhitungan untuk periode ini.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="signature-wrap">
            <div>Mengetahui,</div>
            <div class="signature-space"></div>
            <div><strong>Manajer</strong></div>
        </div>

        <div class="footer-note">
            Dokumen ini dihasilkan secara otomatis oleh sistem SPK TOPSIS.
        </div>
    </div>
</body>

</html>