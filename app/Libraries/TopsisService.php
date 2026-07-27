<?php

namespace App\Libraries;

class TopsisService
{
    public function calculate(array $criteria, array $alternatives): array
    {
        if (empty($criteria) || empty($alternatives)) {
            return $this->emptyResult($criteria, $alternatives);
        }

        $criteriaCount = count($criteria);

        $validatedAlternatives = [];

        foreach ($alternatives as $alt) {
            $scores = $alt['scores'] ?? [];

            if (!is_array($scores) || count($scores) !== $criteriaCount) {
                continue;
            }

            $validatedAlternatives[] = [
                'id' => $alt['id'] ?? null,
                'kode' => $alt['kode'] ?? '',
                'nama' => $alt['nama'] ?? '',
                'scores' => array_map('floatval', $scores),
            ];
        }

        $alternatives = $validatedAlternatives;

        if (count($alternatives) < 2) {
            return $this->emptyResult($criteria, $alternatives);
        }

       // ======================================================
// STEP 2
// NORMALISASI BOBOT KRITERIA
// (Mengikuti Excel)
// ======================================================


$weights = [];

foreach ($criteria as $criterion) {

    $weights[] = ((float)$criterion['bobot']) / 100;

}



// ======================================================
// STEP 3
// PENYEBUT NORMALISASI
// Rumus Excel : SQRT(SUMSQ())
// ======================================================

$divisors = [];

for ($col = 0; $col < $criteriaCount; $col++) {

    $sumSquare = 0;

    foreach ($alternatives as $alternative) {

        $nilai = (float)$alternative['scores'][$col];

        $sumSquare += pow($nilai,2);

    }

    $divisors[$col] = sqrt($sumSquare);

}



// ======================================================
// STEP 4
// MATRIKS KEPUTUSAN TERNORMALISASI
// Rumus Excel : IFERROR(nilai/pembagi,0)
// ======================================================

$normalized = [];

foreach($alternatives as $row=>$alternative){

    foreach($alternative['scores'] as $col=>$nilai){

        if($divisors[$col]==0){

            $normalized[$row][$col]=0;

        }else{

            $normalized[$row][$col]=$nilai/$divisors[$col];

        }

    }

}
       // ======================================================
// STEP 5
// MATRIKS TERNORMALISASI BERBOBOT
// Rumus Excel : yij = rij × wj
// ======================================================

$weighted = [];

foreach ($normalized as $rowIndex => $row) {

    foreach ($row as $colIndex => $value) {

        $weighted[$rowIndex][$colIndex] =
            (float)$value *
            (float)$weights[$colIndex];

    }

}

       // ======================================================
// STEP 6
// SOLUSI IDEAL POSITIF (A+) DAN NEGATIF (A-)
// ======================================================

$idealPositive = [];
$idealNegative = [];

for ($col = 0; $col < $criteriaCount; $col++) {

    // Ambil semua nilai pada kolom ke-$col
    $column = array_column($weighted, $col);

    // Jika kosong, isi dengan 0
    if (empty($column)) {
        $column = [0];
    }

    // Jenis kriteria
    $type = strtolower($criteria[$col]['tipe'] ?? 'benefit');

    if ($type === 'benefit') {

        // Benefit
        $idealPositive[$col] = max($column);
        $idealNegative[$col] = min($column);

    } else {

        // Cost
        $idealPositive[$col] = min($column);
        $idealNegative[$col] = max($column);

    }

}

       // ======================================================
// STEP 7
// MENGHITUNG JARAK KE SOLUSI IDEAL
// D+ DAN D-
// Rumus :
// D+ = √Σ(yij - A+)²
// D- = √Σ(yij - A-)²
// ======================================================

$results = [];

foreach ($alternatives as $rowIndex => $alternative) {

    $dPlus = 0;
    $dMinus = 0;

    foreach ($weighted[$rowIndex] as $colIndex => $value) {

        $dPlus += pow(
            $value - $idealPositive[$colIndex],
            2
        );

        $dMinus += pow(
            $value - $idealNegative[$colIndex],
            2
        );

    }

    $dPlus = sqrt($dPlus);
    $dMinus = sqrt($dMinus);

    // ==================================================
    // STEP 8
    // NILAI PREFERENSI
    // Vi = D- / (D+ + D-)
    // ==================================================

    if (($dPlus + $dMinus) == 0) {

        $preference = 0;

    } else {

        $preference = $dMinus / ($dPlus + $dMinus);

    }

    $results[] = [

        'id' => $alternative['id'],

        'kode' => $alternative['kode'],

        'nama' => $alternative['nama'],

        'd_plus' => $dPlus,

        'd_minus' => $dMinus,

        'preferensi' => $preference,

    ];

}
      // ======================================================
// STEP 9
// RANKING
// Mengurutkan nilai preferensi terbesar
// ======================================================

usort($results, function ($a, $b) {

    return $b['preferensi'] <=> $a['preferensi'];

});

foreach ($results as $index => &$row) {

    $row['ranking'] = $index + 1;

}

unset($row);

        unset($result);

        return [
            'criteria' => $criteria,
            'weights' => $weights,
            'alternatives' => $alternatives,
            'divisors' => $divisors,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'idealPositive' => $idealPositive,
            'idealNegative' => $idealNegative,
            'results' => $results,
        ];
    }

    private function emptyResult(array $criteria, array $alternatives): array
    {
        return [
            'criteria' => $criteria,
            'weights' => [],
            'alternatives' => $alternatives,
            'divisors' => [],
            'normalized' => [],
            'weighted' => [],
            'idealPositive' => [],
            'idealNegative' => [],
            'results' => [],
        ];
    }
}