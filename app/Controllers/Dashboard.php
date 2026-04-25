<?php

namespace App\Controllers;

use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $user = (new UserModel())->find(session()->get('user_id'));
        return $this->view('dashboard/index', [
            'title' => 'Dashboard - CariIn',
            'user'  => $user,
        ], 'layouts/dashboard');
    }

    public function profile()
    {
        $user = (new UserModel())->find(session()->get('user_id'));
        return $this->view('dashboard/profile', [
            'title' => 'Profil Saya - CariIn',
            'user'  => $user,
        ], 'layouts/dashboard');
    }

    public function updateProfile()
    {
        $userModel = new UserModel();
        $userId = (int) session()->get('user_id');
        $user = $userModel->find($userId);
        if (! $user) {
            return redirect()->to('/login');
        }

        $name  = trim((string) $this->request->getPost('name'));
        $phone = trim((string) $this->request->getPost('phone'));

        if ($name === '' || strlen($name) < 2) {
            return redirect()->back()->with('error', 'Nama minimal 2 karakter.');
        }

        $update = [
            'name'  => $name,
            'phone' => $phone ?: null,
        ];

        // Handle avatar upload
        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                return redirect()->back()->with('error', 'Format foto harus JPG, PNG, atau WEBP.');
            }
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran foto maksimal 2MB.');
            }
            $uploadDir = FCPATH . 'assets/uploads/avatars/';
            if (! is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);
            $newName = 'u' . $userId . '_' . uniqid() . '.' . $ext;
            $file->move($uploadDir, $newName);
            $update['avatar_url'] = base_url('assets/uploads/avatars/' . $newName);
        }

        $userModel->update($userId, $update);

        // Refresh session
        session()->set([
            'name'       => $update['name'],
            'avatar_url' => $update['avatar_url'] ?? $user['avatar_url'],
        ]);

        return redirect()->to('/dashboard/profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword()
    {
        $userId = (int) session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if (! $user) {
            return redirect()->to('/login');
        }

        $current = (string) $this->request->getPost('current_password');
        $new     = (string) $this->request->getPost('new_password');
        $confirm = (string) $this->request->getPost('confirm_password');

        if (strlen($new) < 6) {
            return redirect()->back()->with('error', 'Password baru minimal 6 karakter.');
        }
        if ($new !== $confirm) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        // If user already has password, require current password
        if (! empty($user['password_hash'])) {
            if ($current === '' || ! password_verify($current, $user['password_hash'])) {
                return redirect()->back()->with('error', 'Password lama salah.');
            }
        }

        $userModel->update($userId, [
            'password_hash' => password_hash($new, PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/dashboard/profile')->with('success', 'Password berhasil diubah.');
    }
}
