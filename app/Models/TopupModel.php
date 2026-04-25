<?php

namespace App\Models;

use CodeIgniter\Model;

class TopupModel extends Model
{
    protected $table         = 'topups';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id', 'transaction_id', 'amount_rp', 'points',
        'payment_method', 'status', 'snap_token', 'note', 'paid_at',
    ];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';

    public function forUser(int $userId): array
    {
        return $this->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();
    }
}
