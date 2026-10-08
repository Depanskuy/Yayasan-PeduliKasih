<?php
// app/Models/Beneficiary.php
namespace App\Models;

use App\Core\Model;

class Beneficiary extends Model {
    protected string $table = 'beneficiaries';

    public function getAll(?string $category = null, ?string $status = null): array {
        $sql = "SELECT * FROM beneficiaries WHERE deleted_at IS NULL";
        $params = [];
        if ($category) {
            $sql .= " AND category = :cat";
            $params[':cat'] = $category;
        }
        if ($status) {
            $sql .= " AND eligibility_status = :status";
            $params[':status'] = $status;
        }
        $sql .= " ORDER BY id DESC";
        return $this->fetchAll($sql, $params);
    }

    public function findById(int $id): ?array {
        return $this->fetch("SELECT * FROM beneficiaries WHERE id = :id AND deleted_at IS NULL LIMIT 1", [':id' => $id]);
    }

    public function createBeneficiary(array $data): int {
        return $this->insert($this->table, [
            'nik' => $data['nik'],
            'name' => $data['name'],
            'category' => $data['category'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'],
            'village' => $data['village'] ?? null,
            'district' => $data['district'] ?? null,
            'city' => $data['city'] ?? 'Jakarta',
            'eligibility_status' => $data['eligibility_status'] ?? 'verified',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function updateBeneficiary(int $id, array $data): bool {
        return $this->update($this->table, $id, [
            'nik' => $data['nik'],
            'name' => $data['name'],
            'category' => $data['category'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'],
            'village' => $data['village'] ?? null,
            'district' => $data['district'] ?? null,
            'city' => $data['city'] ?? 'Jakarta',
            'eligibility_status' => $data['eligibility_status'] ?? 'verified',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function getAssistanceHistory(int $beneficiaryId): array {
        $sql = "SELECT d.*, c.title AS campaign_title, u.name AS recorded_by_name 
                FROM disbursements d
                JOIN campaigns c ON d.campaign_id = c.id
                JOIN users u ON d.recorded_by = u.id
                WHERE d.beneficiary_id = :bid
                ORDER BY d.disbursement_date DESC";
        return $this->fetchAll($sql, [':bid' => $beneficiaryId]);
    }
}
