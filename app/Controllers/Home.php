<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\PropertyModel;

class Home extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $categoryModel = new CategoryModel();
        $db = \Config\Database::connect();

        // Stats for hero
        $totalProperties = $propertyModel->where('status', 'published')->countAllResults();
        $totalAgents     = $db->table('users')->where('role', 'member')->countAllResults();
        $totalSuccess    = $propertyModel->where('status', 'sold')->countAllResults();
        $citiesRow       = $db->table('properties')->select('COUNT(DISTINCT city) AS n')->where('status', 'published')->get()->getRowArray();

        // Categories with count
        $cats = $categoryModel->getActive();
        foreach ($cats as &$c) {
            $c['count'] = $db->table('properties')->where('category_id', $c['id'])->where('status', 'published')->countAllResults();
        }
        unset($c);

        $data = [
            'title'      => 'CariIn - Temukan Properti Impianmu',
            'featured'   => $propertyModel->getFeatured(8),
            'categories' => $cats,
            'stats'      => [
                'total_properties' => $totalProperties,
                'total_agents'     => $totalAgents,
                'total_success'    => max((int)($citiesRow['n'] ?? 0) * 3, $totalSuccess),
                'total_cities'     => (int) ($citiesRow['n'] ?? 0),
            ],
        ];

        return $this->view('home/index', $data);
    }

    public function search()
    {
        $propertyModel = new PropertyModel();
        $categoryModel = new CategoryModel();

        $filters = [
            'type'      => $this->request->getGet('type'),
            'category'  => $this->request->getGet('category'),
            'province'  => $this->request->getGet('province'),
            'city'      => $this->request->getGet('city'),
            'keyword'   => $this->request->getGet('q'),
            'min_price' => $this->request->getGet('min_price'),
            'max_price' => $this->request->getGet('max_price'),
            'page'      => $this->request->getGet('page') ?? 1,
        ];

        $result = $propertyModel->getPublishedList($filters, 12);

        $data = [
            'title'      => 'Pencarian Properti - CariIn',
            'filters'    => $filters,
            'result'     => $result,
            'categories' => $categoryModel->getActive(),
        ];

        return $this->view('home/search', $data);
    }

    public function byCategory(string $slug)
    {
        $propertyModel = new PropertyModel();
        $categoryModel = new CategoryModel();

        $category = $categoryModel->findBySlug($slug);
        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $filters = [
            'category' => $slug,
            'page'     => $this->request->getGet('page') ?? 1,
        ];
        $result = $propertyModel->getPublishedList($filters, 12);

        $data = [
            'title'      => $category['name'] . ' - CariIn',
            'filters'    => $filters,
            'result'     => $result,
            'categories' => $categoryModel->getActive(),
            'category'   => $category,
        ];

        return $this->view('home/search', $data);
    }
}
