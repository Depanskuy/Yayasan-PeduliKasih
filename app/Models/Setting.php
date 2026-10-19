<?php
// app/Models/Setting.php
namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    public function all(): array
    {
        $rows = $this->fetchAll('SELECT setting_key, setting_value FROM settings');
        $out = [];
        foreach ($rows as $row) {
            $out[$row['setting_key']] = $row['setting_value'];
        }
        return $out;
    }

    public function get(string $key, $default = null)
    {
        $value = $this->scalar('SELECT setting_value FROM settings WHERE setting_key = ?', [$key], null);
        return $value ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->query(
            'INSERT INTO settings (setting_key, setting_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)',
            [$key, $value]
        );
    }
}
