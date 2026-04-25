<?php

namespace App\Controllers;

use App\Models\PropertyModel;

class Compare extends BaseController
{
    private const SESS_KEY = 'compare_ids';
    private const MAX = 4;

    public function index()
    {
        $ids = session()->get(self::SESS_KEY) ?? [];
        $rows = [];
        if (! empty($ids)) {
            $db = \Config\Database::connect();
            $rows = $db->table('properties p')
                ->select('p.*, c.name AS category_name,
                          (SELECT file_name FROM property_images WHERE property_id = p.id ORDER BY is_cover DESC LIMIT 1) AS cover_image,
                          u.name AS seller_name, u.phone AS seller_phone')
                ->join('categories c', 'c.id = p.category_id', 'left')
                ->join('users u', 'u.id = p.user_id', 'left')
                ->whereIn('p.id', $ids)
                ->where('p.status', 'published')
                ->get()->getResultArray();
        }
        return $this->view('compare/index', [
            'title' => 'Bandingkan Properti - CariIn',
            'rows'  => $rows,
        ]);
    }

    public function add(int $propertyId)
    {
        $ids = session()->get(self::SESS_KEY) ?? [];
        if (! in_array($propertyId, $ids, true)) {
            if (count($ids) >= self::MAX) {
                return $this->response->setJSON(['ok' => false, 'message' => 'Maksimal ' . self::MAX . ' properti.']);
            }
            $ids[] = $propertyId;
            session()->set(self::SESS_KEY, $ids);
        }
        return $this->response->setJSON(['ok' => true, 'count' => count($ids)]);
    }

    public function remove(int $propertyId)
    {
        $ids = session()->get(self::SESS_KEY) ?? [];
        $ids = array_values(array_filter($ids, fn($x) => (int) $x !== $propertyId));
        session()->set(self::SESS_KEY, $ids);
        return redirect()->back();
    }

    public function clear()
    {
        session()->remove(self::SESS_KEY);
        return redirect()->to('/');
    }
}
