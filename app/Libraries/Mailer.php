<?php

namespace App\Libraries;

use Config\Email as EmailConfig;
use CodeIgniter\Email\Email;

/**
 * Wrapper sederhana untuk kirim email via CI4 Email service.
 * Konfigurasi diambil dari .env (mail.* keys).
 */
class Mailer
{
    private Email $email;

    public function __construct()
    {
        $config = config(EmailConfig::class);

        // Override dari .env jika ada
        $config->protocol  = (string) env('mail.protocol', $config->protocol);
        $config->SMTPHost  = (string) env('mail.SMTPHost', '');
        $config->SMTPPort  = (int)    env('mail.SMTPPort', 587);
        $config->SMTPUser  = (string) env('mail.SMTPUser', '');
        $config->SMTPPass  = (string) env('mail.SMTPPass', '');
        $config->SMTPCrypto = (string) env('mail.SMTPCrypto', 'tls');
        $config->fromEmail = (string) env('mail.fromEmail', 'noreply@carin.id');
        $config->fromName  = (string) env('mail.fromName', 'CariIn');
        $config->mailType  = 'html';
        $config->charset   = 'UTF-8';
        $config->wordWrap  = true;

        $this->email = \Config\Services::email($config);
        $this->email->setFrom($config->fromEmail, $config->fromName);
    }

    public function send(string $to, string $subject, string $htmlBody): bool
    {
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($htmlBody);
        return $this->email->send(false);
    }

    public function getDebug(): string
    {
        return $this->email->printDebugger(['headers', 'subject', 'body']);
    }

    /**
     * Apakah SMTP sudah dikonfigurasi (cek minimum host & user).
     */
    public static function isConfigured(): bool
    {
        return env('mail.SMTPHost') && env('mail.SMTPUser');
    }
}
