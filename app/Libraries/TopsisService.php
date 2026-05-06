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

        // 1. Ambil bobot awal dari data kriteria
        $rawWeights = [];

        foreach ($criteria as $criterion) {
            $rawWeights[] = (float) ($criterion['bobot'] ?? 0);
        }

        // 2. Normalisasi bobot
        $totalWeight = array_sum($rawWeights);
        $weights = [];

        foreach ($rawWeights as $weight) {
            $weights[] = $totalWeight > 0 ? $weight / $totalWeight : 0;
        }

        // 3. Hitung divisor / pembagi normalisasi matriks keputusan
        $divisors = [];

        for ($i = 0; $i < $criteriaCount; $i++) {
            $sumSquares = 0.0;

            foreach ($alternatives as $alt) {
                $sumSquares += pow((float) ($alt['scores'][$i] ?? 0), 2);
            }

            $divisors[$i] = $sumSquares > 0 ? sqrt($sumSquares) : 1;
        }

        // 4. Matriks ternormalisasi
        $normalized = [];

        foreach ($alternatives as $rowIndex => $alt) {
            for ($colIndex = 0; $colIndex < $criteriaCount; $colIndex++) {
                $score = (float) ($alt['scores'][$colIndex] ?? 0);
                $normalized[$rowIndex][$colIndex] = $divisors[$colIndex] != 0
                    ? $score / $divisors[$colIndex]
                    : 0;
            }
        }

        // 5. Matriks ternormalisasi berbobot
        $weighted = [];

        foreach ($normalized as $rowIndex => $row) {
            for ($colIndex = 0; $colIndex < $criteriaCount; $colIndex++) {
                $weighted[$rowIndex][$colIndex] = ($row[$colIndex] ?? 0) * ($weights[$colIndex] ?? 0);
            }
        }

        // 6. Solusi ideal positif dan negatif
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

        // 7. Hitung D+, D-, dan nilai preferensi
        $results = [];

        foreach ($alternatives as $rowIndex => $alt) {
            $dPlus = 0.0;
            $dMinus = 0.0;

            for ($colIndex = 0; $colIndex < $criteriaCount; $colIndex++) {
                $value = $weighted[$rowIndex][$colIndex] ?? 0;

                $dPlus += pow($value - $idealPositive[$colIndex], 2);
                $dMinus += pow($value - $idealNegative[$colIndex], 2);
            }

            $dPlus = sqrt($dPlus);
            $dMinus = sqrt($dMinus);

            $preference = ($dPlus + $dMinus) > 0
                ? $dMinus / ($dPlus + $dMinus)
                : 0;

            $results[] = [
                'id' => $alt['id'],
                'kode' => $alt['kode'],
                'nama' => $alt['nama'],
                'd_plus' => $dPlus,
                'd_minus' => $dMinus,
                'preferensi' => $preference,
            ];
        }

        // 8. Ranking berdasarkan nilai preferensi terbesar
        usort($results, function ($a, $b) {
            return $b['preferensi'] <=> $a['preferensi'];
        });

        foreach ($results as $index => &$result) {
            $result['ranking'] = $index + 1;
        }

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