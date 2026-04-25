<?php

namespace App\Controllers;

use App\Models\PropertyModel;

class Sitemap extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $props = $db->table('properties')->select('slug, updated_at')->where('status', 'published')->get()->getResultArray();
        $cats  = $db->table('categories')->select('slug, updated_at')->where('is_active', 1)->get()->getResultArray();

        $base = rtrim(base_url(), '/');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $static = ['/', '/search', '/agen', '/panduan', '/blog'];
        foreach ($static as $u) {
            $xml .= '  <url><loc>' . $base . $u . '</loc><changefreq>weekly</changefreq></url>' . "\n";
        }
        foreach ($cats as $c) {
            $xml .= '  <url><loc>' . $base . '/category/' . htmlspecialchars($c['slug']) . '</loc><changefreq>weekly</changefreq></url>' . "\n";
        }
        foreach ($props as $p) {
            $lastmod = ! empty($p['updated_at']) ? date('Y-m-d', strtotime($p['updated_at'])) : date('Y-m-d');
            $xml .= '  <url><loc>' . $base . '/property/' . htmlspecialchars($p['slug']) . '</loc><lastmod>' . $lastmod . '</lastmod><changefreq>weekly</changefreq></url>' . "\n";
        }
        $xml .= '</urlset>';

        return $this->response->setHeader('Content-Type', 'application/xml')->setBody($xml);
    }
}
