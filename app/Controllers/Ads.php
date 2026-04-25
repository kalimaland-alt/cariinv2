<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\PropertyDetailModel;
use App\Models\PropertyImageModel;
use App\Models\PropertyModel;
use App\Models\UserModel;

class Ads extends BaseController
{
    public function index()
    {
        $model = new PropertyModel();
        $db = \Config\Database::connect();
        $rows = $db->table('properties p')
            ->select('p.*, c.name AS category_name,
                      (SELECT file_name FROM property_images WHERE property_id = p.id ORDER BY is_cover DESC LIMIT 1) AS cover_image')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('p.user_id', session()->get('user_id'))
            ->orderBy('p.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->view('ads/index', [
            'title' => 'Iklan Saya - CariIn',
            'rows'  => $rows,
        ], 'layouts/dashboard');
    }

    public function create()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));

        $propertyModel = new PropertyModel();
        $adCount = $propertyModel->where('user_id', $user['id'])->countAllResults();

        // Slot pricing logic
        $settings = new \App\Models\SettingModel();
        $slotPrice = (int) $settings->getValue('slot_price_points', '20');
        $balance   = (int) ($user['points_balance'] ?? 0);

        // If free slot used → require points
        if ($user['free_slot_used'] == 1 && $balance < $slotPrice) {
            return redirect()->to('/topup')->with('error',
                "Slot gratis sudah terpakai. Anda butuh {$slotPrice} poin untuk pasang iklan baru. Saldo Anda: {$balance} poin."
            );
        }

        $categoryModel = new CategoryModel();
        return $this->view('ads/create', [
            'title'        => 'Pasang Iklan Baru - CariIn',
            'categories'   => $categoryModel->getActive(),
            'freeSlotUsed' => (bool) $user['free_slot_used'],
            'adCount'      => $adCount,
            'slotPrice'    => $slotPrice,
            'balance'      => $balance,
        ], 'layouts/dashboard');
    }

    public function store()
    {
        $req = $this->request;
        $userId = session()->get('user_id');

        // Basic fields
        $title = trim((string) $req->getPost('title'));
        $description = trim((string) $req->getPost('description'));
        $categoryId = (int) $req->getPost('category_id');
        $type = $req->getPost('type') === 'rent' ? 'rent' : 'sell';

        // Parse price: support "1.000.000" or "1,000,000" input
        $rawPrice = (string) $req->getPost('price');
        $price = (int) preg_replace('/[^0-9]/', '', $rawPrice);

        // Force price_period = '-' when type is 'sell' (jual tidak butuh periode)
        $pricePeriod = $type === 'sell' ? '-' : ($req->getPost('price_period') ?: 'monthly');

        if ($title === '' || $categoryId === 0 || $price <= 0) {
            return redirect()->back()->with('error', 'Judul, kategori, dan harga wajib diisi.')->withInput();
        }

        $propertyModel = new PropertyModel();
        $slug = $propertyModel->makeUniqueSlug($title);

        $payload = [
            'user_id'      => $userId,
            'category_id'  => $categoryId,
            'type'         => $type,
            'title'        => $title,
            'slug'         => $slug,
            'description'  => $description,
            'price'        => $price,
            'price_period' => $pricePeriod,
            'legal_status' => $req->getPost('legal_status') ?: null,
            'doc_status'   => $req->getPost('doc_status') ?: 'on_hand',
            'orientation'  => $req->getPost('orientation') ?: null,
            'province'     => $req->getPost('province'),
            'city'         => $req->getPost('city'),
            'district'     => $req->getPost('district'),
            'address'      => $req->getPost('address'),
            'village'      => $req->getPost('village'),
            'latitude'     => $req->getPost('latitude') ?: null,
            'longitude'    => $req->getPost('longitude') ?: null,
            'maps_url'     => $req->getPost('maps_url'),
            'status'       => 'pending_review',
        ];

        $propertyId = $propertyModel->insert($payload, true);

        // Save details (dynamic fields)
        $detailModel = new PropertyDetailModel();
        $detailKeys = ['land_area', 'building_area', 'bedrooms', 'bathrooms', 'floors', 'kitchens',
                       'front_yard', 'back_yard', 'fence', 'land_shape', 'road_width', 'road_access'];
        $details = [];
        foreach ($detailKeys as $k) {
            $v = $req->getPost($k);
            if ($v !== null && $v !== '') {
                $details[$k] = $v;
            }
        }
        if (! empty($details)) {
            $detailModel->saveDetails($propertyId, $details);
        }

        // Handle image uploads - robust version with error logging
        $imageModel = new PropertyImageModel();
        $files = $req->getFileMultiple('images') ?: [];
        $uploadDir = FCPATH . 'assets/uploads/properties/';
        if (! is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);

        $sortOrder = 0;
        $uploadErrors = [];
        foreach ($files as $file) {
            if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) continue;
            if (! $file->isValid()) {
                $uploadErrors[] = $file->getErrorString();
                continue;
            }
            if ($file->hasMoved()) continue;

            // Use client extension (works more reliably in CI4) with MIME fallback
            $ext = strtolower($file->getClientExtension() ?: $file->getExtension() ?: '');
            if ($ext === '') {
                $mime = $file->getMimeType();
                $mimeMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                $ext = $mimeMap[$mime] ?? '';
            }
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $uploadErrors[] = "Format file tidak didukung: {$file->getClientName()}";
                continue;
            }
            if ($file->getSize() > 5 * 1024 * 1024) {
                $uploadErrors[] = "{$file->getClientName()} terlalu besar (maks 5MB).";
                continue;
            }

            try {
                $newName = 'p' . $propertyId . '_' . uniqid() . '.' . $ext;
                $file->move($uploadDir, $newName);
                $imageModel->insert([
                    'property_id' => $propertyId,
                    'file_name'   => $newName,
                    'is_cover'    => $sortOrder === 0 ? 1 : 0,
                    'sort_order'  => $sortOrder++,
                ]);
            } catch (\Throwable $e) {
                log_message('error', 'Image upload failed: ' . $e->getMessage());
                $uploadErrors[] = 'Gagal menyimpan foto: ' . $e->getMessage();
            }
        }

        // Mark free slot used (first time) OR deduct points (slot 2+)
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if ((int)($user['free_slot_used'] ?? 0) === 0) {
            $userModel->update($userId, ['free_slot_used' => 1]);
        } else {
            $settings = new \App\Models\SettingModel();
            $slotPrice = (int) $settings->getValue('slot_price_points', '20');
            $newBalance = max(0, (int)($user['points_balance'] ?? 0) - $slotPrice);
            $userModel->update($userId, ['points_balance' => $newBalance]);
            // Mark property as paid slot
            $propertyModel->update($propertyId, ['is_paid_slot' => 1]);
        }

        $msg = 'Iklan berhasil dibuat & masuk antrian moderasi admin!';
        if (! empty($uploadErrors)) {
            $msg .= ' Catatan: ' . implode('; ', $uploadErrors);
            return redirect()->to('/my-ads')->with('info', $msg);
        }
        return redirect()->to('/my-ads')->with('success', $msg);
    }

    public function edit(string $hash)
    {
        $id = unhashid($hash);
        if ($id <= 0) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $model = new PropertyModel();
        $p = $model->find($id);
        if (! $p || $p['user_id'] != session()->get('user_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $details = (new PropertyDetailModel())->getByProperty($id);
        $images  = (new PropertyImageModel())->getByProperty($id);
        $categories = (new CategoryModel())->getActive();

        return $this->view('ads/edit', [
            'title'      => 'Edit Iklan - CariIn',
            'p'          => $p,
            'details'    => $details,
            'images'     => $images,
            'categories' => $categories,
        ], 'layouts/dashboard');
    }

    public function update(string $hash)
    {
        $id = unhashid($hash);
        if ($id <= 0) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $model = new PropertyModel();
        $existing = $model->find($id);
        if (! $existing || $existing['user_id'] != session()->get('user_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $req = $this->request;
        $title = trim((string) $req->getPost('title'));
        $type  = $req->getPost('type') === 'rent' ? 'rent' : 'sell';
        $rawPrice = (string) $req->getPost('price');
        $price = (int) preg_replace('/[^0-9]/', '', $rawPrice);
        $pricePeriod = $type === 'sell' ? '-' : ($req->getPost('price_period') ?: 'monthly');

        if ($title === '' || $price <= 0) {
            return redirect()->back()->with('error', 'Judul dan harga wajib diisi.');
        }

        $update = [
            'title'        => $title,
            'description'  => trim((string) $req->getPost('description')),
            'type'         => $type,
            'price'        => $price,
            'price_period' => $pricePeriod,
            'category_id'  => (int) $req->getPost('category_id') ?: $existing['category_id'],
            'legal_status' => $req->getPost('legal_status') ?: null,
            'orientation'  => $req->getPost('orientation') ?: null,
            'province'     => $req->getPost('province') ?: $existing['province'],
            'city'         => $req->getPost('city') ?: $existing['city'],
            'district'     => $req->getPost('district') ?: $existing['district'],
            'village'      => $req->getPost('village') ?: $existing['village'] ?? null,
            'address'      => $req->getPost('address'),
            'latitude'     => $req->getPost('latitude') ?: $existing['latitude'],
            'longitude'    => $req->getPost('longitude') ?: $existing['longitude'],
            'maps_url'     => $req->getPost('maps_url') ?: $existing['maps_url'],
            'status'       => 'pending_review', // re-moderate after edit
            'reject_reason'=> null,
        ];
        $model->update($id, $update);

        // Update details
        $detailModel = new PropertyDetailModel();
        $detailKeys = ['land_area', 'building_area', 'bedrooms', 'bathrooms', 'floors', 'kitchens',
                       'front_yard', 'back_yard', 'fence', 'land_shape', 'road_width', 'road_access'];
        $details = [];
        foreach ($detailKeys as $k) {
            $v = $req->getPost($k);
            if ($v !== null && $v !== '') $details[$k] = $v;
        }
        if (! empty($details)) $detailModel->saveDetails($id, $details);

        // Handle new image uploads (append, jangan replace)
        $imageModel = new PropertyImageModel();
        $files = $req->getFileMultiple('images') ?: [];
        $uploadDir = FCPATH . 'assets/uploads/properties/';
        if (! is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);

        $existingImages = $imageModel->getByProperty($id);
        $sortOrder = count($existingImages);

        foreach ($files as $file) {
            if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) continue;
            if (! $file->isValid() || $file->hasMoved()) continue;
            $ext = strtolower($file->getClientExtension() ?: $file->getExtension() ?: '');
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) continue;
            if ($file->getSize() > 5 * 1024 * 1024) continue;
            try {
                $newName = 'p' . $id . '_' . uniqid() . '.' . $ext;
                $file->move($uploadDir, $newName);
                $imageModel->insert([
                    'property_id' => $id,
                    'file_name'   => $newName,
                    'is_cover'    => $sortOrder === 0 ? 1 : 0,
                    'sort_order'  => $sortOrder++,
                ]);
            } catch (\Throwable $e) {
                log_message('error', 'Edit image upload failed: ' . $e->getMessage());
            }
        }

        return redirect()->to('/my-ads')->with('success', 'Iklan berhasil diupdate. Status kembali ke pending review.');
    }

   public function deleteImage(string $hash)
    {
        $imageId = unhashid($hash);
        if ($imageId <= 0) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $imageModel = new PropertyImageModel();
        $img = $imageModel->find($imageId);
        if (! $img) return redirect()->back();

        $propModel = new PropertyModel();
        $prop = $propModel->find($img['property_id']);
        if (! $prop || $prop['user_id'] != session()->get('user_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Delete file from disk
        $path = FCPATH . 'assets/uploads/properties/' . $img['file_name'];
        if (is_file($path)) @unlink($path);
        $imageModel->delete($imageId);

        return redirect()->back()->with('success', 'Foto dihapus.');
    }

    public function delete(string $hash)
    {
        $id = unhashid($hash);
        if ($id <= 0) return redirect()->to('/my-ads')->with('error', 'Iklan tidak valid.');

        $model = new PropertyModel();
        $p = $model->find($id);
        if ($p && $p['user_id'] == session()->get('user_id')) {
            $model->delete($id);
            return redirect()->to('/my-ads')->with('success', 'Iklan dihapus.');
        }
        return redirect()->to('/my-ads')->with('error', 'Iklan tidak ditemukan.');
    }

    public function uploadImage()
    {
        return $this->response->setJSON(['status' => 'ok', 'message' => 'Upload lewat form store saja.']);
    }
}
