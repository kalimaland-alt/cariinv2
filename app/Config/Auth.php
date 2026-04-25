<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Custom configuration for Google OAuth & Auth system
 */
class Auth extends BaseConfig
{
    public string $googleClientId = '';
    public string $googleClientSecret = '';
    public string $googleRedirectUri = '';

    public function __construct()
    {
        parent::__construct();
        $this->googleClientId = env('google.clientId', '');
        $this->googleClientSecret = env('google.clientSecret', '');
        $this->googleRedirectUri = env('google.redirectUri', '');
    }
}
