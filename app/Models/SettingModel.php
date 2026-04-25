<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table         = 'settings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['key', 'value'];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';

    public function getValue(string $key, string $default = ''): string
    {
        $row = $this->where('key', $key)->first();
        return $row['value'] ?? $default;
    }

    public function setValue(string $key, string $value): void
    {
        $existing = $this->where('key', $key)->first();
        if ($existing) {
            $this->update($existing['id'], ['value' => $value]);
        } else {
            $this->insert(['key' => $key, 'value' => $value]);
        }
    }

    public function getAll(): array
    {
        $rows = $this->findAll();
        $map = [];
        foreach ($rows as $r) {
            $map[$r['key']] = $r['value'];
        }
        return $map;
    }
}
