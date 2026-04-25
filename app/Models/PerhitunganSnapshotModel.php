<?php

namespace App\Models;

use CodeIgniter\Model;

class PerhitunganSnapshotModel extends Model
{
    protected $table = 'perhitungan_snapshot';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'periode',
        'source_hash',
        'snapshot_json',
        'calculated_at',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}