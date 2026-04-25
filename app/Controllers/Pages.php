<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\PropertyModel;
use App\Models\UserModel;

class Pages extends BaseController
{
    public function agen()
    {
        $db = \Config\Database::connect();
        $agents = $db->table('users u')
            ->select('u.id, u.name, u.avatar_url, u.phone, u.email, u.created_at,
                      (SELECT COUNT(*) FROM properties WHERE user_id = u.id AND status = "published") AS listing_count')
            ->where('u.role', 'member')
            ->where('u.status', 'active')
            ->orderBy('listing_count', 'DESC')
            ->get(24)
            ->getResultArray();

        return $this->view('pages/agen', [
            'title'  => 'Agen Terpercaya - CariIn',
            'agents' => $agents,
        ]);
    }

    public function panduan()
    {
        return $this->view('pages/panduan', ['title' => 'Panduan - CariIn']);
    }

    public function blog()
    {
        return $this->view('pages/blog', ['title' => 'Blog - CariIn']);
    }
}
