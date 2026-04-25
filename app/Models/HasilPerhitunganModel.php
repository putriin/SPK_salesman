<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilPerhitunganModel extends Model
{
    protected $table = 'hasil_perhitungan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'periode',
        'salesman_id',
        'nilai_preferensi',
        'ranking',
        'd_plus',
        'd_minus',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}