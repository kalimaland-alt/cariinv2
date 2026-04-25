<?php
namespace App\Models;
use CodeIgniter\Model;
class WishlistModel extends Model
{
    protected $table         = 'wishlists';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['user_id', 'property_id', 'created_at'];

    protected $useTimestamps = false;

    public function isWished(int $userId, int $propertyId): bool
    {
        return (bool) $this->where(['user_id' => $userId, 'property_id' => $propertyId])->first();
    }

    public function toggle(int $userId, int $propertyId): bool
    {
        if ($this->isWished($userId, $propertyId)) {
            $this->where(['user_id' => $userId, 'property_id' => $propertyId])->delete();
            return false;
        }
        $this->insert([
            'user_id'     => $userId,
            'property_id' => $propertyId,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
        return true;
    }
}