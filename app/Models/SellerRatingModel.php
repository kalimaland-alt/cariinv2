<?php
namespace App\Models;
use CodeIgniter\Model;
class SellerRatingModel extends Model
{
    protected $table         = 'seller_ratings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['seller_id', 'reviewer_id', 'property_id', 'rating', 'review', 'created_at'];

    protected $useTimestamps = false;

    public function summaryForSeller(int $sellerId): array
    {
        $db = \Config\Database::connect();
        $row = $db->table('seller_ratings')
            ->select('AVG(rating) AS avg_rating, COUNT(*) AS total')
            ->where('seller_id', $sellerId)
            ->get()->getRowArray();
        return [
            'avg'   => (float) ($row['avg_rating'] ?? 0),
            'total' => (int)   ($row['total'] ?? 0),
        ];
    }

    public function listForSeller(int $sellerId, int $limit = 10): array
    {
        $db = \Config\Database::connect();
        return $db->table('seller_ratings r')
            ->select('r.*, u.name AS reviewer_name, u.avatar_url AS reviewer_avatar')
            ->join('users u', 'u.id = r.reviewer_id', 'left')
            ->where('r.seller_id', $sellerId)
            ->orderBy('r.created_at', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data) && empty($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        return parent::insert($data, $returnID);
    }
}