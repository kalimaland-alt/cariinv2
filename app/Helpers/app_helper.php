<?php

if (! function_exists('rupiah')) {
    /**
     * Format integer to Indonesian Rupiah.
     */
    function rupiah($number, bool $withPrefix = true): string
    {
        $n = (int) $number;
        $str = number_format($n, 0, ',', '.');
        return $withPrefix ? 'Rp ' . $str : $str;
    }
}

if (! function_exists('price_compact')) {
    /**
     * Compact price (e.g. 1.500.000.000 -> Rp 1,5 M).
     */
    function price_compact($number): string
    {
        $n = (int) $number;
        if ($n >= 1_000_000_000) {
            return 'Rp ' . rtrim(rtrim(number_format($n / 1_000_000_000, 1, ',', '.'), '0'), ',') . ' M';
        }
        if ($n >= 1_000_000) {
            return 'Rp ' . rtrim(rtrim(number_format($n / 1_000_000, 1, ',', '.'), '0'), ',') . ' Jt';
        }
        if ($n >= 1_000) {
            return 'Rp ' . number_format($n / 1_000, 0, ',', '.') . ' Rb';
        }
        return 'Rp ' . number_format($n, 0, ',', '.');
    }
}

if (! function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        return rtrim(base_url(), '/') . '/assets/' . ltrim($path, '/');
    }
}

if (! function_exists('property_image_url')) {
    function property_image_url(?string $filename): string
    {
        if (! $filename) {
            return asset_url('img/placeholder.svg');
        }
        // Support external URLs (e.g. demo seeds)
        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }
        return asset_url('uploads/properties/' . $filename);
    }
}

if (! function_exists('auth_user')) {
    function auth_user(): ?array
    {
        $session = session();
        if (! $session->get('user_id')) {
            return null;
        }
        return [
            'id'         => $session->get('user_id'),
            'email'      => $session->get('email'),
            'name'       => $session->get('name'),
            'role'       => $session->get('role'),
            'avatar_url' => $session->get('avatar_url'),
        ];
    }
}

if (! function_exists('is_admin')) {
    function is_admin(): bool
    {
        return session()->get('role') === 'admin';
    }
}

if (! function_exists('orientation_label')) {
    function orientation_label(?string $code): string
    {
        $map = [
            'N'  => 'Utara',
            'S'  => 'Selatan',
            'E'  => 'Timur',
            'W'  => 'Barat',
            'NE' => 'Timur Laut',
            'NW' => 'Barat Laut',
            'SE' => 'Tenggara',
            'SW' => 'Barat Daya',
        ];
        return $map[$code] ?? '-';
    }
}

if (! function_exists('wa_link')) {
    function wa_link(?string $phone, string $text = ''): string
    {
        if (! $phone) {
            return '#';
        }
        // normalize phone
        $p = preg_replace('/\D/', '', $phone);
        if (str_starts_with($p, '0')) {
            $p = '62' . substr($p, 1);
        }
        $u = 'https://wa.me/' . $p;
        if ($text !== '') {
            $u .= '?text=' . rawurlencode($text);
        }
        return $u;
    }
}
