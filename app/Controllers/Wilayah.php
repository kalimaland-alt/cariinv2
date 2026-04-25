<?php

namespace App\Controllers;

class Wilayah extends BaseController
{
    public function provinces()
    {
        return $this->fetchTable('reg_provinces', null, null);
    }

    public function regencies(string $provinceId)
    {
        return $this->fetchTable('reg_regencies', 'province_id', $provinceId);
    }

    public function districts(string $regencyId)
    {
        return $this->fetchTable('reg_districts', 'regency_id', $regencyId);
    }

    public function villages(string $districtId)
    {
        return $this->fetchTable('reg_villages', 'district_id', $districtId);
    }

    private function fetchTable(string $table, ?string $whereCol, ?string $whereVal)
    {
        try {
            $db = \Config\Database::connect();
            $builder = $db->table($table)->select('id, name');
            if ($whereCol && $whereVal !== null) {
                $builder->where($whereCol, $whereVal);
            }
            $rows = $builder->orderBy('name', 'ASC')->get()->getResultArray();
            return $this->response->setJSON($rows);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
        }
    }
}