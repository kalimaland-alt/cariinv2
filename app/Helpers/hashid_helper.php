<?php

/**
 * Simple reversible ID hashing untuk hide numeric IDs di URL.
 * - hashid(int) → string base62 ~6-8 char
 * - unhashid(string) → int (atau 0 kalau invalid)
 *
 * Catatan: ini OBFUSCATION, bukan kriptografi. Cukup untuk:
 * - Mencegah enumeration ID berurutan
 * - Membuat URL terlihat \"acak\"
 * NOT untuk security/auth yang sensitif.
 *
 * Ganti SECRET di .env (app.hashid_secret) untuk production.
 */

if (! function_exists('hashid')) {
    function hashid(int $id): string
    {
        if ($id <= 0) return '';
        $secret = (int) (env('app.hashid_secret') ?: 0xC4F2A1E7);
        $mixed  = $id ^ $secret;
        // pack as 32-bit unsigned, encode urlsafe-base64, strip padding
        $bin = pack('N', $mixed);
        return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
    }
}

if (! function_exists('unhashid')) {
    function unhashid(string $hash): int
    {
        if ($hash === '') return 0;
        $hash .= str_repeat('=', (4 - strlen($hash) % 4) % 4);
        $bin = base64_decode(strtr($hash, '-_', '+/'));
        if ($bin === false || strlen($bin) !== 4) return 0;
        $arr = unpack('N', $bin);
        $mixed = $arr[1] ?? 0;
        $secret = (int) (env('app.hashid_secret') ?: 0xC4F2A1E7);
        return $mixed ^ $secret;
    }
}