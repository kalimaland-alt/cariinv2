<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyDetailModel extends Model
{
    protected $table         = 'property_details';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['property_id', 'key_name', 'value'];
    protected $useTimestamps = false;

    /**
     * Save (replace) all detail keys for a property.
     *
     * @param array<string,mixed> $keyValues
     */
    public function saveDetails(int $propertyId, array $keyValues): void
    {
        $this->where('property_id', $propertyId)->delete();

        $rows = [];
        foreach ($keyValues as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $rows[] = [
                'property_id' => $propertyId,
                'key_name'    => $key,
                'value'       => is_bool($value) ? ($value ? '1' : '0') : (string) $value,
            ];
        }
        if (! empty($rows)) {
            $this->insertBatch($rows);
        }
    }

    /**
     * Get all key-value pairs for a property as associative array.
     *
     * @return array<string,string>
     */
    public function getByProperty(int $propertyId): array
    {
        $rows = $this->where('property_id', $propertyId)->findAll();
        $out  = [];
        foreach ($rows as $r) {
            $out[$r['key_name']] = $r['value'];
        }
        return $out;
    }
}
