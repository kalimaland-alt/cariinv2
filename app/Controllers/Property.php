<?php

namespace App\Controllers;

use App\Models\PropertyDetailModel;
use App\Models\PropertyImageModel;
use App\Models\PropertyModel;
use App\Models\SellerRatingModel;
use App\Models\WishlistModel;

class Property extends BaseController
{
    public function detail(string $slug)
    {
        $propertyModel = new PropertyModel();
        $detailModel   = new PropertyDetailModel();
        $imageModel    = new PropertyImageModel();

        $property = $propertyModel->findBySlugFull($slug);

        if (! $property || $property['status'] !== 'published') {
            if (
                ! $property
                || ($property['user_id'] !== session()->get('user_id') && ! is_admin())
            ) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        $propertyModel->incrementViews((int) $property['id']);

        $db = \Config\Database::connect();
        $similar = $db->table('properties p')
            ->select('p.*, c.name AS category_name, c.slug AS category_slug,
                      (SELECT file_name FROM property_images WHERE property_id = p.id ORDER BY is_cover DESC LIMIT 1) AS cover_image')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('p.status', 'published')
            ->where('p.category_id', $property['category_id'])
            ->where('p.id !=', $property['id'])
            ->orderBy('p.published_at', 'DESC')
            ->limit(4)
            ->get()
            ->getResultArray();

        // Wishlist state
        $isWished = false;
        $userId = (int) session()->get('user_id');
        if ($userId) {
            $isWished = (new WishlistModel())->isWished($userId, (int) $property['id']);
        }

        // Seller rating summary
        $ratingSummary = (new SellerRatingModel())->summaryForSeller((int) $property['user_id']);
        $ratingList = (new SellerRatingModel())->listForSeller((int) $property['user_id'], 5);

        $data = [
            'title'         => $property['title'] . ' - CariIn',
            'property'      => $property,
            'details'       => $detailModel->getByProperty((int) $property['id']),
            'images'        => $imageModel->getByProperty((int) $property['id']),
            'similar'       => $similar,
            'isWished'      => $isWished,
            'ratingSummary' => $ratingSummary,
            'ratingList'    => $ratingList,
        ];

        return $this->view('property/detail', $data);
    }
}
