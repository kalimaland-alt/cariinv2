<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $users     = $userModel->orderBy('created_at', 'DESC')->findAll();

        return $this->view('admin/users', [
            'title' => 'Manajemen User - CariIn Admin',
            'users' => $users,
        ], 'layouts/admin');
    }

    public function suspend(int $id)
    {
        (new UserModel())->update($id, ['status' => 'suspended']);
        return redirect()->back()->with('success', 'User berhasil di-suspend.');
    }

    public function activate(int $id)
    {
        (new UserModel())->update($id, ['status' => 'active']);
        return redirect()->back()->with('success', 'User berhasil diaktifkan.');
    }
}
