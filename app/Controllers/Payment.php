<?php

namespace App\Controllers;

/**
 * Stub controller - full implementation arrives in Iterasi 2.
 */
class Payment extends BaseController
{
    public function newSlot()
    {
        return $this->view('common/placeholder', [
            'title'   => 'Beli Slot Iklan - CariIn',
            'heading' => 'Beli Slot Iklan (Rp 20.000)',
            'message' => 'Pembayaran QRIS via Midtrans akan tersedia di Iterasi 2.',
        ], 'layouts/dashboard');
    }

    public function createSnap()
    {
        return $this->response->setJSON(['status' => 'pending', 'message' => 'Tersedia di Iterasi 2']);
    }

    public function finish()
    {
        return redirect()->to('/dashboard')->with('info', 'Tersedia di Iterasi 2.');
    }

    public function notification()
    {
        return $this->response->setJSON(['status' => 'ok']);
    }
}
