<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesmanModel extends Model
{
    protected $table = 'salesman';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'kode_alternatif',
        'nama',
        'gender',
        'alamat',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}