<?php

namespace App\Libraries;

class TopsisService
{
    /**
     * Hitung TOPSIS
     * - $criteria harus ada field 'bobot_normalisasi'
     * - $alternatives array of ['id','kode','nama','scores'=>[]]
     */
    public function calculate(array $criteria, array $alternatives): array
    {
        if (empty($criteria) || empty($alternatives)) {
            return [
                'criteria'=> $criteria,
                'weights'=> [],
                'alternatives'=> $alternatives,
                'divisors'=> [],
                'normalized'=> [],
                'weighted'=> [],
                'idealPositive'=> [],
                'idealNegative'=> [],
                'results'=> [],
            ];
        }

        $criteriaCount = count($criteria);

        // Validasi alternatif
        $validatedAlternatives = [];
        foreach ($alternatives as $alt) {
            $scores = $alt['scores'] ?? [];
            if (!is_array($scores) || count($scores) !== $criteriaCount) continue;

            $validatedAlternatives[] = [
                'id'=>$alt['id'] ?? null,
                'kode'=>$alt['kode'] ?? '',
                'nama'=>$alt['nama'] ?? '',
                'scores'=>array_map('floatval', $scores),
            ];
        }
        $alternatives = $validatedAlternatives;

        if (count($alternatives) < 2) {
            return [
                'criteria'=> $criteria,
                'weights'=> [],
                'alternatives'=> $alternatives,
                'divisors'=> [],
                'normalized'=> [],
                'weighted'=> [],
                'idealPositive'=> [],
                'idealNegative'=> [],
                'results'=> [],
            ];
        }

        // 1. Hitung divisor
        $divisors = [];
        for ($i=0;$i<$criteriaCount;$i++) {
            $sumSquares = 0.0;
            foreach ($alternatives as $alt) $sumSquares += pow($alt['scores'][$i] ?? 0, 2);
            $divisors[$i] = $sumSquares>0 ? sqrt($sumSquares) : 1;
        }

        // 2. Gunakan bobot_normalisasi langsung
        $weights = [];
        foreach ($criteria as $criterion) {
            $weights[] = (float) ($criterion['bobot_normalisasi'] ?? 0);
        }

        // 3. Normalisasi matriks
        $normalized = [];
        foreach ($alternatives as $rowIndex=>$alt) {
            for ($colIndex=0;$colIndex<$criteriaCount;$colIndex++) {
                $score = (float) ($alt['scores'][$colIndex] ?? 0);
                $normalized[$rowIndex][$colIndex] = $divisors[$colIndex]!=0 ? $score/$divisors[$colIndex] : 0;
            }
        }

        // 4. Matriks berbobot
        $weighted=[];
        foreach ($normalized as $rowIndex=>$row) {
            for ($colIndex=0;$colIndex<$criteriaCount;$colIndex++) {
                $weighted[$rowIndex][$colIndex] = ($row[$colIndex] ?? 0) * ($weights[$colIndex] ?? 0);
            }
        }

        // 5. Solusi ideal positif/negatif
        $idealPositive = [];
        $idealNegative = [];
        for ($i=0;$i<$criteriaCount;$i++) {
            $columnValues = array_column($weighted,$i) ?: [0];
            $type = strtolower($criteria[$i]['tipe'] ?? 'benefit');
            if ($type==='cost') {
                $idealPositive[$i]=min($columnValues);
                $idealNegative[$i]=max($columnValues);
            } else {
                $idealPositive[$i]=max($columnValues);
                $idealNegative[$i]=min($columnValues);
            }
        }

        // 6. Hitung jarak dan preferensi
        $results=[];
        foreach ($alternatives as $rowIndex=>$alt) {
            $dPlus=0;$dMinus=0;
            for ($colIndex=0;$colIndex<$criteriaCount;$colIndex++) {
                $value = $weighted[$rowIndex][$colIndex] ?? 0;
                $dPlus += pow($value-$idealPositive[$colIndex],2);
                $dMinus += pow($value-$idealNegative[$colIndex],2);
            }
            $dPlus = sqrt($dPlus); $dMinus = sqrt($dMinus);
            $preference = ($dPlus+$dMinus)>0 ? $dMinus/($dPlus+$dMinus):0;
            $results[] = [
                'id'=>$alt['id'],
                'kode'=>$alt['kode'],
                'nama'=>$alt['nama'],
                'd_plus'=>$dPlus,
                'd_minus'=>$dMinus,
                'preferensi'=>$preference,
            ];
        }

        // 7. Ranking
        usort($results,function($a,$b){return $b['preferensi']<=>$a['preferensi'];});
        foreach($results as $index=>&$result) $result['ranking']=$index+1;
        unset($result);

        return [
            'criteria'=>$criteria,
            'weights'=>$weights,
            'alternatives'=>$alternatives,
            'divisors'=>$divisors,
            'normalized'=>$normalized,
            'weighted'=>$weighted,
            'idealPositive'=>$idealPositive,
            'idealNegative'=>$idealNegative,
            'results'=>$results,
        ];
    }
}