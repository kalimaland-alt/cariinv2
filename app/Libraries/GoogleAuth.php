<?php

namespace App\Libraries;

use Config\Auth as AuthConfig;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;

/**
 * Thin wrapper for Google OAuth 2.0 using league/oauth2-google.
 */
class GoogleAuth
{
    protected Google $provider;
    protected AuthConfig $config;

    public function __construct()
    {
        $this->config   = config(AuthConfig::class);
        $this->provider = new Google([
            'clientId'     => $this->config->googleClientId,
            'clientSecret' => $this->config->googleClientSecret,
            'redirectUri'  => $this->config->googleRedirectUri,
        ]);
    }

    /**
     * Build authorization URL and return it (also stores state in session).
     */
    public function getAuthUrl(): string
    {
        $authUrl = $this->provider->getAuthorizationUrl([
            'scope' => ['openid', 'email', 'profile'],
        ]);
        session()->set('oauth2state', $this->provider->getState());
        return $authUrl;
    }

    /**
     * Validate state and exchange code for user info.
     *
     * @throws \RuntimeException
     */
    public function handleCallback(string $code, ?string $state): GoogleUser
    {
        $sessionState = session()->get('oauth2state');
        if (! $state || $state !== $sessionState) {
            session()->remove('oauth2state');
            throw new \RuntimeException('Invalid OAuth state.');
        }
        session()->remove('oauth2state');

        $token = $this->provider->getAccessToken('authorization_code', ['code' => $code]);

        /** @var GoogleUser $user */
        $user = $this->provider->getResourceOwner($token);
        return $user;
    }
}
