<?php

namespace App\Models;

use CodeIgniter\Model;

class PenilaianModel extends Model
{
    protected $table = 'penilaian';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'periode',
        'salesman_id',
        'kriteria_id',
        'nilai',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}