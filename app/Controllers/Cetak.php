<?php

namespace App\Controllers;

use App\Models\HasilPerhitunganModel;
use App\Models\SalesmanModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Cetak extends BaseController
{
    private function getCetakData(string $periode): array
    {
        $hasilModel = new HasilPerhitunganModel();
        $salesmanModel = new SalesmanModel();

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

        return [
            'periode' => $periode,
            'periodeOptions' => $periodeOptions,
            'results' => $results,
            'winner' => $results[0] ?? null,
            'title' => 'Cetak Hasil TOPSIS',
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