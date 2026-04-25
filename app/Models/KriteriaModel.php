<?php

namespace App\Models;

use CodeIgniter\Model;

class KriteriaModel extends Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'kode_kriteria',
        'nama_kriteria',
        'tipe',
        'bobot',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}