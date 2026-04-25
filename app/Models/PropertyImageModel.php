<?php
namespace App\Models;
use CodeIgniter\Model;
class PropertyImageModel extends Model
{
    protected $table         = 'property_images';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['property_id', 'file_name', 'is_cover', 'sort_order', 'created_at'];

    // Disable auto timestamps karena tabel ini hanya punya created_at, tidak punya updated_at
    protected $useTimestamps = false;

    public function getByProperty(int $propertyId): array
    {
        return $this->where('property_id', $propertyId)
            ->orderBy('is_cover', 'DESC')
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }

    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data) && empty($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        return parent::insert($data, $returnID);
    }
}
