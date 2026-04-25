<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatMessageModel extends Model
{
    protected $table         = 'chat_messages';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['chat_id', 'sender_id', 'message', 'is_read'];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $updatedField  = '';

    public function listForChat(int $chatId): array
    {
        $db = \Config\Database::connect();
        return $db->table('chat_messages m')
            ->select('m.*, u.name AS sender_name, u.avatar_url AS sender_avatar')
            ->join('users u', 'u.id = m.sender_id', 'left')
            ->where('m.chat_id', $chatId)
            ->orderBy('m.created_at', 'ASC')
            ->get()->getResultArray();
    }
}
