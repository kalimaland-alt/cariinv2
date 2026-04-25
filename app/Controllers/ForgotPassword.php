<?php

namespace App\Controllers;

use App\Libraries\Mailer;
use App\Models\UserModel;

class ForgotPassword extends BaseController
{
    public function showForgot()
    {
        return $this->view('auth/forgot', ['title' => 'Lupa Password - CariIn'], 'layouts/blank');
    }

    public function sendReset()
    {
        $email = trim((string) $this->request->getPost('email'));
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Email tidak valid.');
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        // Selalu tampilkan pesan sama untuk hindari email enumeration
        if (! $user) {
            return redirect()->to('/forgot-password')->with('success', 'Jika email terdaftar, link reset password telah dikirim.');
        }

        $token = bin2hex(random_bytes(24));
        $userModel->update($user['id'], [
            'reset_token'   => $token,
            'reset_expires' => date('Y-m-d H:i:s', time() + 60 * 60), // 1 jam
        ]);

        $resetUrl = site_url('/reset-password/' . $token);
        $appName  = 'CariIn';
        $body = "
            <div style='font-family:Inter,Arial,sans-serif;max-width:560px;margin:auto;padding:24px;border:1px solid #e5e7eb;border-radius:12px;'>
                <h2 style='color:#065F46;'>Reset Password {$appName}</h2>
                <p>Halo <strong>" . esc($user['name']) . "</strong>,</p>
                <p>Kami menerima permintaan reset password untuk akun Anda. Klik tombol di bawah untuk membuat password baru. Link berlaku 1 jam.</p>
                <p style='text-align:center;margin:24px 0;'>
                    <a href='{$resetUrl}' style='background:#10B981;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:bold;'>Reset Password</a>
                </p>
                <p style='font-size:12px;color:#6B7280;'>Atau copy-paste URL ini ke browser:<br><span style='word-break:break-all;'>{$resetUrl}</span></p>
                <hr style='border:none;border-top:1px solid #e5e7eb;margin:24px 0;'>
                <p style='font-size:12px;color:#6B7280;'>Jika Anda tidak meminta reset password, abaikan email ini. Akun Anda tetap aman.</p>
            </div>
        ";

        if (Mailer::isConfigured()) {
            try {
                $mailer = new Mailer();
                $sent = $mailer->send($email, "Reset Password {$appName}", $body);
                if (! $sent) {
                    log_message('error', 'Mailer failed: ' . $mailer->getDebug());
                    return redirect()->back()->with('error', 'Gagal mengirim email. Hubungi admin.');
                }
            } catch (\Throwable $e) {
                log_message('error', 'Mailer exception: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
            }
        } else {
            // SMTP belum dikonfigurasi - log link untuk admin
            log_message('warning', "[FORGOT_PASSWORD] SMTP not configured. Manual link for {$email}: {$resetUrl}");
            return redirect()->to('/forgot-password')->with('info', 'SMTP belum dikonfigurasi admin. Hubungi admin untuk reset password manual.');
        }

        return redirect()->to('/forgot-password')->with('success', 'Link reset password telah dikirim ke email Anda. Cek inbox/spam.');
    }

    public function showReset(string $token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)->where('reset_expires >', date('Y-m-d H:i:s'))->first();
        if (! $user) {
            return redirect()->to('/forgot-password')->with('error', 'Link tidak valid atau sudah kadaluarsa.');
        }
        return $this->view('auth/reset', [
            'title' => 'Reset Password - CariIn',
            'token' => $token,
            'email' => $user['email'],
        ], 'layouts/blank');
    }

    public function doReset()
    {
        $token   = (string) $this->request->getPost('token');
        $pass    = (string) $this->request->getPost('password');
        $confirm = (string) $this->request->getPost('password_confirm');

        if (strlen($pass) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.');
        }
        if ($pass !== $confirm) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)->where('reset_expires >', date('Y-m-d H:i:s'))->first();
        if (! $user) {
            return redirect()->to('/forgot-password')->with('error', 'Link tidak valid atau sudah kadaluarsa.');
        }

        $userModel->update($user['id'], [
            'password_hash' => password_hash($pass, PASSWORD_DEFAULT),
            'reset_token'   => null,
            'reset_expires' => null,
        ]);

        return redirect()->to('/login')->with('success', 'Password berhasil di-reset. Silakan login.');
    }
}
