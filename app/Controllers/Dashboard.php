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

        $salesmanModel  = new SalesmanModel();
        $kriteriaModel  = new KriteriaModel();
        $penilaianModel = new PenilaianModel();
        $hasilModel     = new HasilPerhitunganModel();
        $userModel      = new UserModel();

        /*
        |--------------------------------------------------------------------------
        | Statistik Dashboard
        |--------------------------------------------------------------------------
        */

        $totalSalesman = $salesmanModel->countAllResults();

        $totalKriteria = $kriteriaModel->countAllResults();

        $totalNilai = $penilaianModel
            ->select('salesman_id, periode')
            ->distinct()
            ->countAllResults();

        $totalHasilAkhir = $hasilModel->countAllResults();

        $totalUser = $userModel->countAllResults();

        /*
        |--------------------------------------------------------------------------
        | Daftar Periode
        |--------------------------------------------------------------------------
        */

        $periodeRows = $hasilModel
            ->select('periode')
            ->distinct()
            ->orderBy('periode', 'DESC')
            ->findAll();

        $periodeOptions = array_map(
            static fn($row) => $row['periode'],
            $periodeRows
        );

        $selectedPeriode = $this->request->getGet('periode');

        if (empty($selectedPeriode) && !empty($periodeOptions)) {
            $selectedPeriode = $periodeOptions[0];
        }

        /*
        |--------------------------------------------------------------------------
        | Data Grafik
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];
        $chartValues = [];

        $chartTitle = 'Grafik Kinerja Salesman Terbaik';

        /*
        |--------------------------------------------------------------------------
        | Panel Salesman Terbaik
        |--------------------------------------------------------------------------
        */

        $topSalesman = null;

        $topSalesmanPerformance = [];

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
                        /*
            |--------------------------------------------------------------------------
            | Data Grafik
            |--------------------------------------------------------------------------
            */

            foreach ($hasilRows as $row) {

                $chartLabels[] =
                    $salesmanMap[$row['salesman_id']]['nama'] ?? 'Unknown';

                $chartValues[] =
                    (float) ($row['nilai_preferensi'] ?? 0);
            }

            $chartTitle .= ' (Periode ' . $selectedPeriode . ')';

            /*
            |--------------------------------------------------------------------------
            | Salesman Ranking #1
            |--------------------------------------------------------------------------
            */

            $topSalesman = $hasilModel
                ->where('periode', $selectedPeriode)
                ->orderBy('ranking', 'ASC')
                ->first();

            if ($topSalesman) {

                $topSalesman['nama'] =
                    $salesmanMap[$topSalesman['salesman_id']]['nama']
                    ?? '-';

                /*
                |--------------------------------------------------------------------------
                | Mengambil seluruh nilai kriteria salesman terbaik
                |--------------------------------------------------------------------------
                */

                   $nilaiRows = $penilaianModel
                    ->select('
                     penilaian.nilai,
                        kriteria.kode_kriteria,
                        kriteria.nama_kriteria
                    ')
                    ->join(
                        'kriteria',
                        'kriteria.id = penilaian.kriteria_id'
                    )
                    ->where(
                        'penilaian.periode',
                        $selectedPeriode
                    )
                    ->where(
                        'penilaian.salesman_id',
                        $topSalesman['salesman_id']
                    )
                    ->orderBy(
                        'kriteria.kode_kriteria',
                        'ASC'
                    )
                    ->findAll();

               foreach ($nilaiRows as $row) {

    switch ($row['kode_kriteria']) {

        case 'C1':
            $topSalesman['close_order'] = $row['nilai'];
            break;

        case 'C2':
            $topSalesman['kunjungan'] = $row['nilai'];
            break;

        case 'C3':
            $topSalesman['demo'] = $row['nilai'];
            break;

    }
    }}
    

}

        /*
        |--------------------------------------------------------------------------
        | Kirim Data ke View
        |--------------------------------------------------------------------------
        */

        return view('dashboard/index', [

            'title' => 'Dashboard',

            'username' =>
                session()->get('username') ?? 'User',

            'stats' => [
                'salesman'   => $totalSalesman,
                'kriteria'   => $totalKriteria,
                'nilai'      => $totalNilai,
                'hasilAkhir' => $totalHasilAkhir,
                'user'       => $totalUser,
            ],

            'chart' => [
                'labels' => $chartLabels,
                'values' => $chartValues,
                'title'  => $chartTitle,
            ],

            'periodeOptions' => $periodeOptions,

            'selectedPeriode' => $selectedPeriode,

            'topSalesman' => $topSalesman,

            'topSalesmanPerformance' => $topSalesmanPerformance,
                        /*
            |--------------------------------------------------------------------------
            | Tidak ada lagi dashboardInsight
            | Tidak ada lagi topCriteriaInsight
            |--------------------------------------------------------------------------
            */

        ]);
    }
}