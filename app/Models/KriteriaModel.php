<?php

namespace App\Models;

use CodeIgniter\Model;

class KriteriaModel extends Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_kriteria', 'nama_kriteria', 'tipe', 'bobot', 'bobot_normalisasi'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $useSoftDeletes = false; // jika soft delete aktif, ganti true
}