<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;

class Moderation extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $status = $this->request->getGet('status') ?? 'pending_review';
        if (! in_array($status, ['pending_review', 'published', 'rejected', 'sold'], true)) {
            $status = 'pending_review';
        }

        $rows = $db->table('properties p')
            ->select('p.id, p.title, p.slug, p.type, p.price, p.status, p.city, p.created_at,
                      c.name AS category_name, u.name AS seller_name, u.email AS seller_email,
                      (SELECT file_name FROM property_images WHERE property_id = p.id ORDER BY is_cover DESC LIMIT 1) AS cover_image')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->where('p.status', $status)
            ->orderBy('p.created_at', 'DESC')
            ->get(50)
            ->getResultArray();

        // Stats
        $stats = [
            'total'    => $db->table('properties')->countAllResults(),
            'pending'  => $db->table('properties')->where('status', 'pending_review')->countAllResults(),
            'approved' => $db->table('properties')->where('status', 'published')->countAllResults(),
            'rejected' => $db->table('properties')->where('status', 'rejected')->countAllResults(),
        ];

        return $this->view('admin/moderation', [
            'title'          => 'Moderasi Iklan - CariIn Admin',
            'rows'           => $rows,
            'currentStatus'  => $status,
            'stats'          => $stats,
        ], 'layouts/admin');
    }

    public function approve(int $id)
    {
        (new PropertyModel())->update($id, [
            'status'       => 'published',
            'published_at' => date('Y-m-d H:i:s'),
            'reject_reason' => null,
        ]);
        return redirect()->back()->with('success', 'Iklan berhasil disetujui & ditayangkan.');
    }

    public function reject(int $id)
    {
        $reason = trim((string) $this->request->getPost('reason')) ?: 'Ditolak oleh admin.';
        (new PropertyModel())->update($id, [
            'status'        => 'rejected',
            'reject_reason' => $reason,
        ]);
        return redirect()->back()->with('success', 'Iklan ditolak. Pemilik akan mendapat notifikasi.');
    }
}
