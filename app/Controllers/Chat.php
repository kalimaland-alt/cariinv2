<?php

namespace App\Controllers;

use App\Models\ChatMessageModel;
use App\Models\ChatModel;
use App\Models\PropertyModel;

class Chat extends BaseController
{
    public function index()
    {
        $userId = (int) session()->get('user_id');
        $rows = (new ChatModel())->listForUser($userId);
        return $this->view('dashboard/chat', [
            'title'  => 'Pesan Saya - CariIn',
            'rows'   => $rows,
            'userId' => $userId,
        ], 'layouts/dashboard');
    }

    public function start(int $propertyId)
    {
        $userId = (int) session()->get('user_id');
        $property = (new PropertyModel())->find($propertyId);
        if (! $property) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if ((int) $property['user_id'] === $userId) {
            return redirect()->to('/property/' . $property['slug'])->with('info', 'Anda tidak bisa chat ke iklan Anda sendiri.');
        }
        $chat = (new ChatModel())->findOrCreate($propertyId, $userId, (int) $property['user_id']);
        return redirect()->to('/chat/thread/' . $chat['id']);
    }

    public function thread(int $chatId)
    {
        $userId = (int) session()->get('user_id');
        $chatModel = new ChatModel();
        $chat = $chatModel->find($chatId);
        if (! $chat || ((int) $chat['buyer_id'] !== $userId && (int) $chat['seller_id'] !== $userId)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db = \Config\Database::connect();
        $meta = $db->table('chats c')
            ->select('p.title AS property_title, p.slug AS property_slug,
                      ub.name AS buyer_name, us.name AS seller_name,
                      (SELECT file_name FROM property_images WHERE property_id = p.id ORDER BY is_cover DESC LIMIT 1) AS cover_image')
            ->join('properties p', 'p.id = c.property_id', 'left')
            ->join('users ub', 'ub.id = c.buyer_id', 'left')
            ->join('users us', 'us.id = c.seller_id', 'left')
            ->where('c.id', $chatId)
            ->get()->getRowArray();

        $messages = (new ChatMessageModel())->listForChat($chatId);

        // Mark messages as read for me
        $db->table('chat_messages')->where('chat_id', $chatId)->where('sender_id !=', $userId)->update(['is_read' => 1]);

        return $this->view('dashboard/chat_thread', [
            'title'    => 'Pesan: ' . ($meta['property_title'] ?? '-') . ' - CariIn',
            'chat'     => $chat,
            'meta'     => $meta,
            'messages' => $messages,
            'userId'   => $userId,
        ], 'layouts/dashboard');
    }

    public function send(int $chatId)
    {
        $userId = (int) session()->get('user_id');
        $chat = (new ChatModel())->find($chatId);
        if (! $chat || ((int) $chat['buyer_id'] !== $userId && (int) $chat['seller_id'] !== $userId)) {
            return redirect()->to('/chat')->with('error', 'Akses ditolak.');
        }
        $msg = trim((string) $this->request->getPost('message'));
        if ($msg === '') {
            return redirect()->back();
        }
        (new ChatMessageModel())->insert([
            'chat_id'   => $chatId,
            'sender_id' => $userId,
            'message'   => $msg,
            'is_read'   => 0,
        ]);
        (new ChatModel())->update($chatId, ['last_message_at' => date('Y-m-d H:i:s')]);
        return redirect()->to('/chat/thread/' . $chatId);
    }
}
