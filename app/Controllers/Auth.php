<?php

namespace App\Controllers;

use App\Libraries\GoogleAuth;
use App\Libraries\Mailer;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to(is_admin() ? '/admin' : '/dashboard');
        }
        return $this->view('auth/login', ['title' => 'Login - CariIn'], 'layouts/blank');
    }

    public function register()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }
        return $this->view('auth/register', ['title' => 'Daftar Gratis - CariIn'], 'layouts/blank');
    }

    public function doLogin()
    {
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        if ($email === '' || $password === '') {
            return redirect()->back()->with('error', 'Email dan password wajib diisi.')->withInput();
        }

        $user = (new UserModel())->findByEmail($email);
        if (! $user || empty($user['password_hash']) || ! password_verify($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Email atau password salah.')->withInput();
        }
        if ($user['status'] === 'suspended') {
            return redirect()->back()->with('error', 'Akun Anda di-suspend. Hubungi admin.');
        }

        $this->setUserSession($user);
        return redirect()->to($user['role'] === 'admin' ? '/admin' : '/dashboard')
            ->with('success', 'Selamat datang kembali, ' . $user['name'] . '!');
    }

    public function doRegister()
    {
        $data = [
            'name'     => trim((string) $this->request->getPost('name')),
            'email'    => trim((string) $this->request->getPost('email')),
            'phone'    => trim((string) $this->request->getPost('phone')),
            'password' => (string) $this->request->getPost('password'),
            'confirm'  => (string) $this->request->getPost('password_confirm'),
        ];

        if (strlen($data['name']) < 2) {
            return redirect()->back()->with('error', 'Nama minimal 2 karakter.')->withInput();
        }
        if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Email tidak valid.')->withInput();
        }
        if (strlen($data['password']) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.')->withInput();
        }
        if ($data['password'] !== $data['confirm']) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.')->withInput();
        }

        $userModel = new UserModel();
        if ($userModel->findByEmail($data['email'])) {
            return redirect()->back()->with('error', 'Email sudah terdaftar. Silakan login.')->withInput();
        }

        $verifyToken = bin2hex(random_bytes(24));
        $newId = $userModel->insert([
            'email'         => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'name'          => $data['name'],
            'phone'         => $data['phone'] ?: null,
            'role'          => 'member',
            'status'        => 'active',
            'verify_token'  => $verifyToken,
        ], true);

        $user = $userModel->find($newId);

        // Send verification email if SMTP configured
        $verifyUrl = site_url('/verify-email/' . $verifyToken);
        if (Mailer::isConfigured()) {
            try {
                $body = "
                    <div style='font-family:Inter,Arial,sans-serif;max-width:560px;margin:auto;padding:24px;border:1px solid #e5e7eb;border-radius:12px;'>
                        <h2 style='color:#065F46;'>Selamat Datang di CariIn 🏡</h2>
                        <p>Halo <strong>" . esc($user['name']) . "</strong>,</p>
                        <p>Terima kasih sudah mendaftar di CariIn. Klik tombol di bawah untuk verifikasi email Anda:</p>
                        <p style='text-align:center;margin:24px 0;'>
                            <a href='{$verifyUrl}' style='background:#10B981;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:bold;'>Verifikasi Email</a>
                        </p>
                        <p style='font-size:12px;color:#6B7280;'>Atau copy URL ini:<br><span style='word-break:break-all;'>{$verifyUrl}</span></p>
                    </div>
                ";
                (new Mailer())->send($user['email'], 'Verifikasi Email CariIn', $body);
            } catch (\Throwable $e) {
                log_message('error', 'Verify email send failed: ' . $e->getMessage());
            }
        } else {
            log_message('warning', "[VERIFY_EMAIL] SMTP not configured. Manual link for {$user['email']}: {$verifyUrl}");
        }

        $this->setUserSession($user);
        return redirect()->to('/dashboard')->with('success', 'Pendaftaran berhasil! Cek email untuk verifikasi (atau lanjutkan dulu, verify nanti).');
    }

    public function verifyEmail(string $token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('verify_token', $token)->first();
        if (! $user) {
            return redirect()->to('/login')->with('error', 'Link verifikasi tidak valid.');
        }
        $userModel->update($user['id'], [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'verify_token'      => null,
        ]);
        return redirect()->to('/dashboard')->with('success', 'Email berhasil diverifikasi! 🎉');
    }

    public function resendVerify()
    {
        $userId = (int) session()->get('user_id');
        if (! $userId) return redirect()->to('/login');
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if (! $user || ! empty($user['email_verified_at'])) {
            return redirect()->back()->with('info', 'Email sudah terverifikasi.');
        }
        $token = bin2hex(random_bytes(24));
        $userModel->update($userId, ['verify_token' => $token]);

        $verifyUrl = site_url('/verify-email/' . $token);
        if (Mailer::isConfigured()) {
            try {
                $body = "<p>Klik link untuk verifikasi: <a href='{$verifyUrl}'>{$verifyUrl}</a></p>";
                (new Mailer())->send($user['email'], 'Verifikasi Email CariIn (Re-send)', $body);
                return redirect()->back()->with('success', 'Email verifikasi dikirim ulang. Cek inbox/spam.');
            } catch (\Throwable $e) {
                log_message('error', 'Resend verify failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal kirim email.');
            }
        }
        return redirect()->back()->with('info', 'SMTP belum dikonfigurasi. Hubungi admin.');
    }

    public function google()
    {
        try {
            $google  = new GoogleAuth();
            return redirect()->to($google->getAuthUrl());
        } catch (\Throwable $e) {
            log_message('error', 'Google auth init failed: ' . $e->getMessage());
            return redirect()->to('/login')->with('error', 'Gagal memulai login Google. Periksa konfigurasi .env.');
        }
    }

    public function googleCallback()
    {
        $code  = $this->request->getGet('code');
        $state = $this->request->getGet('state');

        if (! $code) {
            return redirect()->to('/login')->with('error', 'Login Google dibatalkan.');
        }

        try {
            $google = new GoogleAuth();
            $gUser  = $google->handleCallback($code, $state);
        } catch (\Throwable $e) {
            log_message('error', 'Google callback error: ' . $e->getMessage());
            return redirect()->to('/login')->with('error', 'Login Google gagal: ' . $e->getMessage());
        }

        $userModel = new UserModel();
        $googleId  = $gUser->getId();
        $email     = $gUser->getEmail();
        $name      = $gUser->getName() ?? 'Unknown';
        $avatar    = $gUser->getAvatar();

        $existing = $userModel->findByGoogleId($googleId) ?? $userModel->findByEmail($email);

        if ($existing) {
            $update = [];
            if (empty($existing['google_id'])) $update['google_id'] = $googleId;
            if ($avatar && $avatar !== $existing['avatar_url']) $update['avatar_url'] = $avatar;
            // Google email langsung verified
            if (empty($existing['email_verified_at'])) $update['email_verified_at'] = date('Y-m-d H:i:s');
            if (! empty($update)) {
                $userModel->update($existing['id'], $update);
                $existing = array_merge($existing, $update);
            }
            $user = $existing;
        } else {
            $newId = $userModel->insert([
                'google_id'         => $googleId,
                'email'             => $email,
                'name'              => $name,
                'avatar_url'        => $avatar,
                'role'              => 'member',
                'status'            => 'active',
                'email_verified_at' => date('Y-m-d H:i:s'),
            ], true);
            $user = $userModel->find($newId);
        }

        if ($user['status'] === 'suspended') {
            return redirect()->to('/login')->with('error', 'Akun Anda sedang di-suspend.');
        }

        $this->setUserSession($user);
        return redirect()->to($user['role'] === 'admin' ? '/admin' : '/dashboard')
            ->with('success', 'Selamat datang, ' . $user['name'] . '!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Anda telah logout.');
    }

    private function setUserSession(array $user): void
    {
        session()->set([
            'user_id'    => $user['id'],
            'email'      => $user['email'],
            'name'       => $user['name'],
            'role'       => $user['role'],
            'avatar_url' => $user['avatar_url'],
            'email_verified' => ! empty($user['email_verified_at']),
        ]);
    }

    /** Dev-only helpers */
    public function devLoginAdmin()
    {
        if (ENVIRONMENT !== 'development') throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $admin = (new UserModel())->where('role', 'admin')->first();
        if ($admin) { $this->setUserSession($admin); return redirect()->to('/admin'); }
        return redirect()->to('/');
    }
    public function devLoginMember()
    {
        if (ENVIRONMENT !== 'development') throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $m = (new UserModel())->where('email', 'demo@carin.local')->first() ?? (new UserModel())->where('role', 'member')->first();
        if ($m) { $this->setUserSession($m); return redirect()->to('/dashboard'); }
        return redirect()->to('/');
    }
}
