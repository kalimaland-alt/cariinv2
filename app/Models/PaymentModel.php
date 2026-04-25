<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table         = 'payments';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id', 'property_id', 'transaction_id', 'snap_token',
        'amount', 'payment_method', 'status', 'paid_at', 'raw_response',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findByTransactionId(string $trxId): ?array
    {
        return $this->where('transaction_id', $trxId)->first();
    }

    public function totalRevenue(): int
    {
        $row = $this->selectSum('amount', 'total')->where('status', 'success')->first();
        return (int) ($row['total'] ?? 0);
    }
}
