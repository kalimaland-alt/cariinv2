<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyModel extends Model
{
    protected $table            = 'properties';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id', 'category_id', 'type', 'title', 'slug', 'description',
        'price', 'price_period', 'legal_status', 'doc_status', 'orientation',
        'province', 'city', 'district', 'village', 'address',
        'latitude', 'longitude', 'maps_url',
        'status', 'reject_reason', 'views', 'is_paid_slot', 'published_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Generate unique slug from title.
     */
    public function makeUniqueSlug(string $title): string
    {
        $base = url_title($title, '-', true);
        $slug = $base;
        $i    = 1;
        while ($this->where('slug', $slug)->first()) {
            $slug = $base . '-' . ($i++);
        }
        return $slug;
    }

    /**
     * Get published properties with first image & category joined.
     */
    public function getPublishedList(array $filters = [], int $perPage = 12): array
    {
        $builder = $this->db->table($this->table . ' p')
            ->select('p.*, c.name AS category_name, c.slug AS category_slug, c.form_type,
                      u.name AS seller_name, u.avatar_url AS seller_avatar, u.phone AS seller_phone,
                      (SELECT file_name FROM property_images WHERE property_id = p.id ORDER BY is_cover DESC, sort_order ASC LIMIT 1) AS cover_image')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->where('p.status', 'published');

        if (! empty($filters['type']) && in_array($filters['type'], ['sell', 'rent'], true)) {
            $builder->where('p.type', $filters['type']);
        }
        if (! empty($filters['category'])) {
            $builder->where('c.slug', $filters['category']);
        }
        if (! empty($filters['city'])) {
            $builder->like('p.city', $filters['city']);
        }
        if (! empty($filters['province'])) {
            $builder->like('p.province', $filters['province']);
        }
        if (! empty($filters['keyword'])) {
            $builder->groupStart()
                ->like('p.title', $filters['keyword'])
                ->orLike('p.description', $filters['keyword'])
                ->orLike('p.city', $filters['keyword'])
                ->groupEnd();
        }
        if (! empty($filters['min_price'])) {
            $builder->where('p.price >=', (int) $filters['min_price']);
        }
        if (! empty($filters['max_price'])) {
            $builder->where('p.price <=', (int) $filters['max_price']);
        }

        $builder->orderBy('p.published_at', 'DESC');

        $page = max(1, (int) ($filters['page'] ?? 1));
        $offset = ($page - 1) * $perPage;

        $total = (clone $builder)->countAllResults(false);
        $rows  = $builder->limit($perPage, $offset)->get()->getResultArray();

        return [
            'data'      => $rows,
            'total'     => $total,
            'page'      => $page,
            'per_page'  => $perPage,
            'last_page' => (int) ceil($total / $perPage),
        ];
    }

    public function getFeatured(int $limit = 8): array
    {
        return $this->db->table($this->table . ' p')
            ->select('p.*, c.name AS category_name, c.slug AS category_slug,
                      (SELECT file_name FROM property_images WHERE property_id = p.id ORDER BY is_cover DESC, sort_order ASC LIMIT 1) AS cover_image')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('p.status', 'published')
            ->orderBy('p.views', 'DESC')
            ->orderBy('p.published_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function findBySlugFull(string $slug): ?array
    {
        $row = $this->db->table($this->table . ' p')
            ->select('p.*, c.name AS category_name, c.slug AS category_slug, c.form_type,
                      u.name AS seller_name, u.avatar_url AS seller_avatar, u.phone AS seller_phone, u.email AS seller_email')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->where('p.slug', $slug)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function incrementViews(int $propertyId): void
    {
        $this->db->table($this->table)->where('id', $propertyId)->increment('views');
    }

    public function countByUser(int $userId): int
    {
        return $this->where('user_id', $userId)->countAllResults();
    }
}
