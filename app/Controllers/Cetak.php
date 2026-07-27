<?php

namespace App\Controllers;

use App\Models\HasilPerhitunganModel;
use App\Models\SalesmanModel;
use App\Models\PenilaianModel;
use App\Models\KriteriaModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Cetak extends BaseController
{
    private function getCetakData(string $periode): array
    {
        $hasilModel = new HasilPerhitunganModel();
        $salesmanModel = new SalesmanModel();
        $penilaianModel = new PenilaianModel();
        $kriteriaModel  = new KriteriaModel();

        $periodeList = $hasilModel
            ->select('periode')
            ->distinct()
            ->orderBy('periode', 'DESC')
            ->findAll();

        $periodeOptions = array_map(static function ($row) {
            return $row['periode'];
        }, $periodeList);

        $rows = $hasilModel
            ->where('periode', $periode)
            ->orderBy('ranking', 'ASC')
            ->findAll();

        $salesmanIds = array_column($rows, 'salesman_id');
        $salesmen = [];

        if (!empty($salesmanIds)) {
            $salesmanData = $salesmanModel
                ->whereIn('id', $salesmanIds)
                ->findAll();

            foreach ($salesmanData as $s) {
                $salesmen[$s['id']] = $s;
            }
        }

        $results = [];
        foreach ($rows as $row) {
            $salesman = $salesmen[$row['salesman_id']] ?? [];

            $results[] = [
                'ranking' => $row['ranking'],
                'kode' => $salesman['kode_alternatif'] ?? $salesman['kode'] ?? '-',
                'nama' => $salesman['nama'] ?? '-',
                'preferensi' => (float) ($row['nilai_preferensi'] ?? 0),
                'd_plus' => (float) ($row['d_plus'] ?? 0),
                'd_minus' => (float) ($row['d_minus'] ?? 0),
            ];
        }

        /*
|--------------------------------------------------------------------------
| Data Salesman Terbaik
|--------------------------------------------------------------------------
*/

$winner = $results[0] ?? null;

$winnerScores = [
    'close_order' => 0,
    'kunjungan'   => 0,
    'demo'        => 0,
];

if ($winner) {

    $criteria = $kriteriaModel
        ->orderBy('kode_kriteria', 'ASC')
        ->findAll();


  $winnerSalesmanId = $rows[0]['salesman_id'] ?? null;

$nilaiMap = [];

if ($winnerSalesmanId) {

    $penilaianRows = $penilaianModel
        ->where('periode', $periode)
        ->where('salesman_id', $winnerSalesmanId)
        ->orderBy('kriteria_id', 'ASC')
        ->findAll();

    foreach ($penilaianRows as $row) {

        $nilaiMap[$row['kriteria_id']] = $row['nilai'];

    }

}

   $winnerScores = [
    'close_order' => 0,
    'kunjungan'   => 0,
    'demo'        => 0,
];

foreach ($criteria as $item) {

    switch ($item['kode_kriteria']) {

        case 'C1':
            $winnerScores['close_order'] =
                $nilaiMap[$item['id']] ?? 0;
            break;

        case 'C2':
            $winnerScores['kunjungan'] =
                $nilaiMap[$item['id']] ?? 0;
            break;

        case 'C3':
            $winnerScores['demo'] =
                $nilaiMap[$item['id']] ?? 0;
            break;
    }
}

}
$periodeLabel = date(
    'F Y',
    strtotime($periode . '-01')
);
       return [
    'periode' => $periode,

    'periodeLabel' => $periodeLabel,

    'periodeOptions' => $periodeOptions,

    'results' => $results,

    'winner' => $winner,

    'winnerScores' => $winnerScores,

    'printedAt' => date('d-m-Y H:i:s'),

    'printedBy' => session()->get('nama')
        ?? session()->get('username')
        ?? 'Manajer',

    'title' => 'Laporan Hasil Perankingan Salesman',
];
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $periode = $this->request->getGet('periode') ?: date('Y-m');
        return view('cetak/index', $this->getCetakData($periode));
    }

    public function downloadPdf()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $periode = $this->request->getGet('periode') ?: date('Y-m');
        $data = $this->getCetakData($periode);

        $html = view('cetak/pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'laporan-topsis-' . $periode . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }
}