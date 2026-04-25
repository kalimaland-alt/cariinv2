<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaymentModel;
use App\Models\PropertyModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel     = new UserModel();
        $propertyModel = new PropertyModel();
        $paymentModel  = new PaymentModel();

        $data = [
            'title' => 'Admin Dashboard - CariIn',
            'stats' => [
                'total_users'       => $userModel->countAllResults(),
                'total_members'     => $userModel->where('role', 'member')->countAllResults(),
                'total_properties'  => $propertyModel->countAllResults(),
                'pending_review'    => $propertyModel->where('status', 'pending_review')->countAllResults(),
                'published'         => $propertyModel->where('status', 'published')->countAllResults(),
                'total_revenue'     => $paymentModel->totalRevenue(),
            ],
        ];

        return $this->view('admin/dashboard', $data, 'layouts/admin');
    }
}
