<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaymentModel;

class Transactions extends BaseController
{
    public function index()
    {
        $model = new PaymentModel();
        $rows  = $model->orderBy('created_at', 'DESC')->findAll(100);

        return $this->view('admin/transactions', [
            'title' => 'Log Transaksi - CariIn Admin',
            'rows'  => $rows,
        ], 'layouts/admin');
    }
}
