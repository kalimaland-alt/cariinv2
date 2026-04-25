<?php

namespace App\Controllers;

use App\Models\WishlistModel;

class Wishlist extends BaseController
{
    public function index()
    {
        $userId = (int) session()->get('user_id');
        $db = \Config\Database::connect();
        $rows = $db->table('wishlists w')
            ->select('p.id, p.title, p.slug, p.type, p.price, p.price_period, p.city, p.province,
                      c.name AS category_name,
                      (SELECT file_name FROM property_images WHERE property_id = p.id ORDER BY is_cover DESC LIMIT 1) AS cover_image,
                      w.created_at AS wished_at')
            ->join('properties p', 'p.id = w.property_id', 'left')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('w.user_id', $userId)
            ->where('p.status', 'published')
            ->orderBy('w.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->view('dashboard/wishlist', [
            'title' => 'Wishlist Saya - CariIn',
            'rows'  => $rows,
        ], 'layouts/dashboard');
    }

    public function toggle(int $propertyId)
    {
        $userId = (int) session()->get('user_id');
        if (! $userId) {
            return $this->response->setJSON(['ok' => false, 'login' => true]);
        }
        $isAdded = (new WishlistModel())->toggle($userId, $propertyId);
        return $this->response->setJSON(['ok' => true, 'added' => $isAdded]);
    }
}
