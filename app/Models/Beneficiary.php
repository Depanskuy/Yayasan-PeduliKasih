<?php
// app/Models/Beneficiary.php
namespace App\Models;

use App\Core\Model;

class Beneficiary extends Model
{
    public function all(array $filters = []): array
    {
        $sql = 'SELECT * FROM beneficiaries WHERE deleted_at IS NULL';
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= ' AND category = ?';
            $params[] = $filters['category'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (name LIKE ? OR nik LIKE ? OR city LIKE ?)';
            $like = '%' . $filters['search'] . '%';
            array_push($params, $like, $like, $like);
        }

        $sql .= ' ORDER BY created_at DESC';
        return $this->fetchAll($sql, $params);
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM beneficiaries WHERE id = ? AND deleted_at IS NULL', [$id]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO beneficiaries
                    (nik, name, category, phone, address, village, district, city, eligibility_status, notes, created_at)
                VALUES
                    (:nik, :name, :category, :phone, :address, :village, :district, :city, :eligibility, :notes, NOW())';
        $this->query($sql, [
            ':nik'         => $data['nik'],
            ':name'        => $data['name'],
            ':category'    => $data['category'],
            ':phone'       => $data['phone'] ?? null,
            ':address'     => $data['address'],
            ':village'     => $data['village'] ?? null,
            ':district'    => $data['district'] ?? null,
            ':city'        => $data['city'] ?? 'Jakarta',
            ':eligibility' => $data['eligibility_status'] ?? 'verified',
            ':notes'       => $data['notes'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function countByCategory(): array
    {
        $rows = $this->fetchAll(
            'SELECT category, COUNT(*) AS total FROM beneficiaries WHERE deleted_at IS NULL GROUP BY category'
        );
        $out = [];
        foreach ($rows as $row) {
            $out[$row['category']] = (int) $row['total'];
        }
        return $out;
    }

    public function total(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM beneficiaries WHERE deleted_at IS NULL');
    }

    public static function categoryLabels(): array
    {
        return [
            'fakir'           => 'Fakir',
            'miskin'          => 'Miskin',
            'yatim'           => 'Yatim',
            'lansia'          => 'Lansia',
            'difabel'         => 'Difabel',
            'korban_bencana'  => 'Korban Bencana',
            'lainnya'         => 'Lainnya',
        ];
    }
}
