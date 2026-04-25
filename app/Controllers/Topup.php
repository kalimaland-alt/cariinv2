<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\TopupModel;
use App\Models\UserModel;

class Topup extends BaseController
{
    public function index()
    {
        $settings = new SettingModel();
        $pointRate = (int) $settings->getValue('point_rate', '1000');

        $packages = [
            ['points' => 10,  'bonus' => 0,  'price' => 10 * $pointRate],
            ['points' => 25,  'bonus' => 2,  'price' => 25 * $pointRate],
            ['points' => 50,  'bonus' => 5,  'price' => 50 * $pointRate],
            ['points' => 100, 'bonus' => 15, 'price' => 100 * $pointRate],
        ];

        $user = (new UserModel())->find(session()->get('user_id'));

        return $this->view('dashboard/topup', [
            'title'     => 'Top Up Poin - CariIn',
            'packages'  => $packages,
            'user'      => $user,
            'pointRate' => $pointRate,
        ], 'layouts/dashboard');
    }

    public function history()
    {
        $rows = (new TopupModel())->forUser((int) session()->get('user_id'));
        return $this->view('dashboard/topup_history', [
            'title' => 'Riwayat Top Up - CariIn',
            'rows'  => $rows,
        ], 'layouts/dashboard');
    }

    public function create()
    {
        $points = (int) $this->request->getPost('points');
        $amount = (int) $this->request->getPost('amount');
        if ($points <= 0 || $amount <= 0) {
            return redirect()->back()->with('error', 'Paket tidak valid.');
        }

        $userId = (int) session()->get('user_id');
        $trxId  = 'TOP-' . strtoupper(uniqid());

        // Try Midtrans Snap if creds available
        $serverKey = env('midtrans.serverKey');
        $clientKey = env('midtrans.clientKey');
        $snapToken = null;
        $method = 'manual_transfer';

        if ($serverKey && $clientKey && str_contains((string) $serverKey, 'Mid-server')) {
            try {
                \Midtrans\Config::$serverKey  = $serverKey;
                \Midtrans\Config::$isProduction = (bool) env('midtrans.isProduction', false);
                \Midtrans\Config::$isSanitized  = true;
                \Midtrans\Config::$is3ds        = true;

                $user = (new UserModel())->find($userId);
                $params = [
                    'transaction_details' => ['order_id' => $trxId, 'gross_amount' => $amount],
                    'customer_details'    => ['email' => $user['email'], 'first_name' => $user['name']],
                    'item_details'        => [[
                        'id'       => 'topup-' . $points,
                        'price'    => $amount,
                        'quantity' => 1,
                        'name'     => "Top Up {$points} Poin CariIn",
                    ]],
                    'enabled_payments' => ['gopay', 'shopeepay', 'qris', 'bca_va', 'bni_va', 'bri_va'],
                ];
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $method = 'qris';
            } catch (\Throwable $e) {
                log_message('error', 'Midtrans Snap failed: ' . $e->getMessage());
                $snapToken = null; // fallback to manual
            }
        }

        $newId = (new TopupModel())->insert([
            'user_id'        => $userId,
            'transaction_id' => $trxId,
            'amount_rp'      => $amount,
            'points'         => $points,
            'payment_method' => $method,
            'snap_token'     => $snapToken,
            'status'         => 'pending',
            'note'           => $snapToken ? 'Snap token created.' : 'Menunggu konfigurasi Midtrans / konfirmasi admin.',
        ], true);

        return redirect()->to('/topup/pay/' . $newId);
    }

    public function pay(int $id)
    {
        $topup = (new TopupModel())->find($id);
        if (! $topup || $topup['user_id'] != session()->get('user_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return $this->view('dashboard/topup_pay', [
            'title'     => 'Pembayaran Top Up',
            'topup'     => $topup,
            'clientKey' => env('midtrans.clientKey'),
            'isProd'    => (bool) env('midtrans.isProduction', false),
        ], 'layouts/dashboard');
    }

    /**
     * Midtrans webhook (no auth filter).
     */
    public function notification()
    {
        $serverKey = env('midtrans.serverKey');
        if (! $serverKey) {
            return $this->response->setJSON(['ok' => false, 'reason' => 'no key']);
        }
        try {
            \Midtrans\Config::$serverKey = $serverKey;
            \Midtrans\Config::$isProduction = (bool) env('midtrans.isProduction', false);
            $notif = new \Midtrans\Notification();
        } catch (\Throwable $e) {
            return $this->response->setJSON(['ok' => false, 'reason' => $e->getMessage()]);
        }

        $orderId = $notif->order_id;
        $status  = $notif->transaction_status;
        $fraud   = $notif->fraud_status ?? '';

        $topupModel = new TopupModel();
        $topup = $topupModel->where('transaction_id', $orderId)->first();
        if (! $topup) {
            return $this->response->setJSON(['ok' => false, 'reason' => 'not found']);
        }

        $newStatus = $topup['status'];
        if (in_array($status, ['capture', 'settlement'], true) && $fraud !== 'challenge') {
            $newStatus = 'success';
        } elseif ($status === 'pending') {
            $newStatus = 'pending';
        } elseif (in_array($status, ['deny', 'cancel', 'expire'], true)) {
            $newStatus = $status === 'expire' ? 'expired' : 'failed';
        }

        // Apply if changed
        if ($newStatus !== $topup['status']) {
            $update = ['status' => $newStatus];
            if ($newStatus === 'success') {
                $update['paid_at'] = date('Y-m-d H:i:s');
                // Add points to user
                $um = new UserModel();
                $u = $um->find($topup['user_id']);
                if ($u) {
                    $um->update($u['id'], ['points_balance' => (int)$u['points_balance'] + (int)$topup['points']]);
                }
            }
            $topupModel->update($topup['id'], $update);
        }

        return $this->response->setJSON(['ok' => true, 'status' => $newStatus]);
    }
}
