<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Categories extends BaseController
{
    public function index()
    {
        $model = new CategoryModel();
        return $this->view('admin/categories', [
            'title'      => 'Kategori - CariIn Admin',
            'categories' => $model->orderBy('sort_order', 'ASC')->findAll(),
        ], 'layouts/admin');
    }

    public function store()
    {
        return redirect()->back()->with('info', 'Akan tersedia di Iterasi 3.');
    }
}
