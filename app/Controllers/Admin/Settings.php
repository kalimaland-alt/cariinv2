<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Settings extends BaseController
{
    public function index()
    {
        $settings = (new SettingModel())->getAll();
        return $this->view('admin/settings', [
            'title'    => 'Pengaturan Footer - CariIn Admin',
            'settings' => $settings,
        ], 'layouts/admin');
    }

    public function save()
    {
        $keys = [
            'footer_description', 'footer_email', 'footer_address', 'footer_phone',
            'footer_facebook', 'footer_instagram', 'footer_twitter', 'footer_copyright',
            'point_rate', 'slot_price_points',
        ];
        $m = new SettingModel();
        foreach ($keys as $k) {
            $val = $this->request->getPost($k);
            if ($val !== null) {
                $m->setValue($k, (string) $val);
            }
        }
        return redirect()->to('/admin/settings')->with('success', 'Pengaturan disimpan.');
    }
}
