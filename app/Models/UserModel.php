<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'google_id', 'email', 'password_hash', 'name', 'avatar_url', 'phone',
        'role', 'status', 'free_slot_used', 'points_balance',
        'email_verified_at', 'verify_token', 'reset_token', 'reset_expires',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'email' => 'required|valid_email',
        'name'  => 'required|min_length[2]|max_length[150]',
    ];

    public function findByGoogleId(string $googleId): ?array
    {
        return $this->where('google_id', $googleId)->first();
    }

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function markFreeSlotUsed(int $userId): bool
    {
        return $this->update($userId, ['free_slot_used' => 1]);
    }
}
