<?php

namespace App\Controllers;

use App\Models\HasilPerhitunganModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;
use App\Models\SalesmanModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $allowedRoles = ['admin', 'manajer', 'ceo'];

        if (!in_array(session()->get('role'), $allowedRoles, true)) {
            return redirect()->to('/login')->with('errors', [
                'login' => 'Role user tidak diizinkan.',
            ]);
        }

        $salesmanModel = new SalesmanModel();
        $kriteriaModel = new KriteriaModel();
        $penilaianModel = new PenilaianModel();
        $hasilModel = new HasilPerhitunganModel();
        $userModel = new UserModel();

        $totalSalesman = $salesmanModel->countAllResults();
        $totalKriteria = $kriteriaModel->countAllResults();
        $totalNilai = $penilaianModel
            ->select('salesman_id, periode')
            ->distinct()
            ->countAllResults();
        $totalHasilAkhir = $hasilModel->countAllResults();
        $totalUser = $userModel->countAllResults();

        $periodeRows = $hasilModel
            ->select('periode')
            ->distinct()
            ->orderBy('periode', 'DESC')
            ->findAll();

        $periodeOptions = array_map(static function ($row) {
            return $row['periode'];
        }, $periodeRows);

        $selectedPeriode = $this->request->getGet('periode');

        if (empty($selectedPeriode) && !empty($periodeOptions)) {
            $selectedPeriode = $periodeOptions[0];
        }

        $chartLabels = [];
        $chartValues = [];
        $chartTitle = 'Grafik Kinerja Salesman Terbaik';

        $topCriteriaInsight = [
            'nama_kriteria' => '-',
            'nilai_tertinggi' => '-',
            'salesman' => '-',
        ];

        if (!empty($selectedPeriode)) {
            $hasilRows = $hasilModel
                ->where('periode', $selectedPeriode)
                ->orderBy('ranking', 'ASC')
                ->findAll();

            $salesmanIds = array_column($hasilRows, 'salesman_id');
            $salesmanMap = [];

            if (!empty($salesmanIds)) {
                $salesmanRows = $salesmanModel
                    ->whereIn('id', $salesmanIds)
                    ->findAll();

                foreach ($salesmanRows as $row) {
                    $salesmanMap[$row['id']] = $row;
                }
            }

            foreach ($hasilRows as $row) {
                $chartLabels[] = $salesmanMap[$row['salesman_id']]['nama'] ?? 'Unknown';
                $chartValues[] = (float) ($row['nilai_preferensi'] ?? 0);
            }

            $chartTitle .= ' (Periode ' . $selectedPeriode . ')';

            $topPenilaian = $penilaianModel
                ->select('penilaian.nilai, penilaian.salesman_id, penilaian.kriteria_id')
                ->where('periode', $selectedPeriode)
                ->orderBy('nilai', 'DESC')
                ->first();

            if (!empty($topPenilaian)) {
                $salesman = $salesmanModel->find($topPenilaian['salesman_id']);
                $kriteria = $kriteriaModel->find($topPenilaian['kriteria_id']);

                $topCriteriaInsight = [
                    'nama_kriteria' => $kriteria['nama_kriteria'] ?? '-',
                    'nilai_tertinggi' => $topPenilaian['nilai'] ?? '-',
                    'salesman' => $salesman['nama'] ?? '-',
                ];
            }
        }

        return view('dashboard/index', [
            'title' => 'Dashboard',
            'username' => session()->get('username') ?? 'User',
            'stats' => [
                'salesman' => $totalSalesman,
                'kriteria' => $totalKriteria,
                'nilai' => $totalNilai,
                'hasilAkhir' => $totalHasilAkhir,
                'user' => $totalUser,
            ],
            'chart' => [
                'labels' => $chartLabels,
                'values' => $chartValues,
                'title' => $chartTitle,
            ],
            'periodeOptions' => $periodeOptions,
            'selectedPeriode' => $selectedPeriode,
            'topCriteriaInsight' => $topCriteriaInsight,
        ]);
    }
}