<?php

namespace App\Libraries;

class TopsisService
{
    public function calculate(array $criteria, array $alternatives): array
    {
        if (empty($criteria) || empty($alternatives)) {
            return [
                'criteria'      => $criteria,
                'weights'       => [],
                'alternatives'  => $alternatives,
                'divisors'      => [],
                'normalized'    => [],
                'weighted'      => [],
                'idealPositive' => [],
                'idealNegative' => [],
                'results'       => [],
            ];
        }

        $criteriaCount = count($criteria);

        // Validasi alternatif: harus punya scores lengkap sesuai jumlah kriteria
        $validatedAlternatives = [];

        foreach ($alternatives as $alt) {
            $scores = $alt['scores'] ?? [];

            if (!is_array($scores)) {
                continue;
            }

            if (count($scores) !== $criteriaCount) {
                continue;
            }

            $validatedAlternatives[] = [
                'id'     => $alt['id'] ?? null,
                'kode'   => $alt['kode'] ?? '',
                'nama'   => $alt['nama'] ?? '',
                'scores' => array_map('floatval', $scores),
            ];
        }

        $alternatives = $validatedAlternatives;

        // Minimal harus ada 2 alternatif valid
        if (count($alternatives) < 2) {
            return [
                'criteria'      => $criteria,
                'weights'       => [],
                'alternatives'  => $alternatives,
                'divisors'      => [],
                'normalized'    => [],
                'weighted'      => [],
                'idealPositive' => [],
                'idealNegative' => [],
                'results'       => [],
            ];
        }

        // 1. Hitung pembagi normalisasi
        $divisors = [];
        for ($i = 0; $i < $criteriaCount; $i++) {
            $sumSquares = 0.0;

            foreach ($alternatives as $alt) {
                $score = (float) ($alt['scores'][$i] ?? 0);
                $sumSquares += pow($score, 2);
            }

            $divisors[$i] = $sumSquares > 0 ? sqrt($sumSquares) : 1;
        }

        // 2. Normalisasi bobot
        $weightTotal = array_sum(array_column($criteria, 'bobot'));
        $weights = [];

        foreach ($criteria as $criterion) {
            $weights[] = $weightTotal > 0
                ? ((float) ($criterion['bobot'] ?? 0) / $weightTotal)
                : 0;
        }

        // 3. Matriks normalisasi
        $normalized = [];
        foreach ($alternatives as $rowIndex => $alt) {
            for ($colIndex = 0; $colIndex < $criteriaCount; $colIndex++) {
                $score = (float) ($alt['scores'][$colIndex] ?? 0);

                $normalized[$rowIndex][$colIndex] = $divisors[$colIndex] != 0
                    ? $score / $divisors[$colIndex]
                    : 0;
            }
        }

        // 4. Matriks ternormalisasi berbobot
        $weighted = [];
        foreach ($normalized as $rowIndex => $row) {
            for ($colIndex = 0; $colIndex < $criteriaCount; $colIndex++) {
                $value = (float) ($row[$colIndex] ?? 0);
                $weighted[$rowIndex][$colIndex] = $value * ($weights[$colIndex] ?? 0);
            }
        }

        // 5. Solusi ideal positif dan negatif
        $idealPositive = [];
        $idealNegative = [];

        for ($i = 0; $i < $criteriaCount; $i++) {
            $columnValues = array_column($weighted, $i);

            if (empty($columnValues)) {
                $columnValues = [0];
            }

            $type = strtolower($criteria[$i]['tipe'] ?? 'benefit');

            if ($type === 'cost') {
                $idealPositive[$i] = min($columnValues);
                $idealNegative[$i] = max($columnValues);
            } else {
                $idealPositive[$i] = max($columnValues);
                $idealNegative[$i] = min($columnValues);
            }
        }

        // 6. Jarak ke solusi ideal + preferensi
        $results = [];
        foreach ($alternatives as $rowIndex => $alt) {
            $dPlus = 0.0;
            $dMinus = 0.0;

            for ($colIndex = 0; $colIndex < $criteriaCount; $colIndex++) {
                $value = (float) ($weighted[$rowIndex][$colIndex] ?? 0);

                $dPlus += pow($value - $idealPositive[$colIndex], 2);
                $dMinus += pow($value - $idealNegative[$colIndex], 2);
            }

            $dPlus = sqrt($dPlus);
            $dMinus = sqrt($dMinus);

            $preference = ($dPlus + $dMinus) > 0
                ? $dMinus / ($dPlus + $dMinus)
                : 0;

            $results[] = [
                'id'         => $alt['id'],
                'kode'       => $alt['kode'],
                'nama'       => $alt['nama'],
                'd_plus'     => $dPlus,
                'd_minus'    => $dMinus,
                'preferensi' => $preference,
            ];
        }

        // 7. Ranking
        usort($results, static function ($a, $b) {
            return $b['preferensi'] <=> $a['preferensi'];
        });

        foreach ($results as $index => &$result) {
            $result['ranking'] = $index + 1;
        }
        unset($result);

        return [
            'criteria'      => $criteria,
            'weights'       => $weights,
            'alternatives'  => $alternatives,
            'divisors'      => $divisors,
            'normalized'    => $normalized,
            'weighted'      => $weighted,
            'idealPositive' => $idealPositive,
            'idealNegative' => $idealNegative,
            'results'       => $results,
        ];
    }
}