<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatMessageModel extends Model
{
    protected $table         = 'chat_messages';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['chat_id', 'sender_id', 'message', 'is_read', 'created_at'];

    protected $useTimestamps = false;

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
    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data) && empty($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        return parent::insert($data, $returnID);
    }
}