<?php
// app/Models/Setting.php
namespace App\Models;

use App\Core\Model;

class Setting extends Model {
    protected string $table = 'settings';

    public function getAllAsMap(): array {
        $rows = $this->fetchAll("SELECT setting_key, setting_value FROM settings");
        $map = [];
        foreach ($rows as $r) {
            $map[$r['setting_key']] = $r['setting_value'];
        }
        return $map;
    }

    public function get(string $key, ?string $default = null): ?string {
        $val = $this->fetchColumn("SELECT setting_value FROM settings WHERE setting_key = :k LIMIT 1", [':k' => $key]);
        return $val !== false ? $val : $default;
    }

    public function set(string $key, string $value, string $group = 'general'): void {
        $exists = $this->fetch("SELECT id FROM settings WHERE setting_key = :k", [':k' => $key]);
        if ($exists) {
            $this->query("UPDATE settings SET setting_value = :v WHERE setting_key = :k", [':v' => $value, ':k' => $key]);
        } else {
            $this->insert($this->table, [
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_group' => $group
            ]);
        }
    }
}
