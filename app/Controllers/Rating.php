<?php

namespace App\Controllers;

use App\Models\PropertyModel;
use App\Models\SellerRatingModel;

class Rating extends BaseController
{
    public function store()
    {
        $userId = (int) session()->get('user_id');
        $propertyId = (int) $this->request->getPost('property_id');
        $rating = max(1, min(5, (int) $this->request->getPost('rating')));
        $review = trim((string) $this->request->getPost('review'));

        $property = (new PropertyModel())->find($propertyId);
        if (! $property || (int) $property['user_id'] === $userId) {
            return redirect()->back()->with('error', 'Tidak bisa rating iklan sendiri.');
        }

        $model = new SellerRatingModel();
        $existing = $model->where([
            'reviewer_id' => $userId,
            'seller_id'   => $property['user_id'],
            'property_id' => $propertyId,
        ])->first();

        if ($existing) {
            $model->update($existing['id'], ['rating' => $rating, 'review' => $review]);
        } else {
            $model->insert([
                'reviewer_id' => $userId,
                'seller_id'   => $property['user_id'],
                'property_id' => $propertyId,
                'rating'      => $rating,
                'review'      => $review,
            ]);
        }
        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
