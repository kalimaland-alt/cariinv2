<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaymentModel;
use App\Models\TopupModel;
use App\Models\UserModel;

class Finance extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Totals
        $totalTopup = (int) ($db->table('topups')->selectSum('amount_rp')->where('status', 'success')->get()->getRow('amount_rp') ?? 0);
        $totalPayments = (int) ($db->table('payments')->selectSum('amount')->where('status', 'success')->get()->getRow('amount') ?? 0);
        $pendingTopup = (int) ($db->table('topups')->where('status', 'pending')->countAllResults());
        $totalUsers = (new UserModel())->countAllResults();

        // Monthly revenue (last 6 months)
        $monthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = date('Y-m-01 00:00:00', strtotime("-{$i} month"));
            $end   = date('Y-m-t 23:59:59', strtotime("-{$i} month"));
            $sumTopup = (int) ($db->table('topups')->selectSum('amount_rp')
                ->where('status', 'success')->where('paid_at >=', $start)->where('paid_at <=', $end)
                ->get()->getRow('amount_rp') ?? 0);
            $sumPay = (int) ($db->table('payments')->selectSum('amount')
                ->where('status', 'success')->where('paid_at >=', $start)->where('paid_at <=', $end)
                ->get()->getRow('amount') ?? 0);
            $monthly[] = [
                'label' => date('M Y', strtotime($start)),
                'value' => $sumTopup + $sumPay,
            ];
        }

        // Latest transactions (combined topups+payments)
        $topups   = (new TopupModel())->orderBy('created_at', 'DESC')->findAll(10);
        $payments = (new PaymentModel())->orderBy('created_at', 'DESC')->findAll(10);

        return $this->view('admin/finance', [
            'title'         => 'Dashboard Keuangan - CariIn Admin',
            'totalTopup'    => $totalTopup,
            'totalPayments' => $totalPayments,
            'totalRevenue'  => $totalTopup + $totalPayments,
            'pendingTopup'  => $pendingTopup,
            'totalUsers'    => $totalUsers,
            'monthly'       => $monthly,
            'topups'        => $topups,
            'payments'      => $payments,
        ], 'layouts/admin');
    }

    public function topupHistory()
    {
        $db = \Config\Database::connect();
        $rows = $db->table('topups t')
            ->select('t.*, u.name AS user_name, u.email AS user_email')
            ->join('users u', 'u.id = t.user_id', 'left')
            ->orderBy('t.created_at', 'DESC')
            ->limit(200)
            ->get()->getResultArray();

        return $this->view('admin/topup_history', [
            'title' => 'Riwayat Top Up - CariIn Admin',
            'rows'  => $rows,
        ], 'layouts/admin');
    }

    public function approveTopup(int $id)
    {
        $topupModel = new TopupModel();
        $topup = $topupModel->find($id);
        if (! $topup) {
            return redirect()->back()->with('error', 'Top up tidak ditemukan.');
        }
        if ($topup['status'] === 'success') {
            return redirect()->back()->with('info', 'Top up sudah disetujui sebelumnya.');
        }

        // Update topup
        $topupModel->update($id, [
            'status'  => 'success',
            'paid_at' => date('Y-m-d H:i:s'),
        ]);

        // Add points to user
        $userModel = new UserModel();
        $user = $userModel->find($topup['user_id']);
        if ($user) {
            $newBalance = (int) ($user['points_balance'] ?? 0) + (int) $topup['points'];
            $userModel->update($user['id'], ['points_balance' => $newBalance]);
        }

        return redirect()->back()->with('success', "Top up disetujui. {$topup['points']} poin ditambahkan.");
    }

    public function rejectTopup(int $id)
    {
        (new TopupModel())->update($id, ['status' => 'failed']);
        return redirect()->back()->with('success', 'Top up ditolak.');
    }
}
