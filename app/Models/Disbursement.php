<?php
// app/Models/Disbursement.php
namespace App\Models;

use App\Core\Model;

class Disbursement extends Model {
    protected string $table = 'disbursements';

    public function generateDisbursementCode(): string {
        $dateStr = date('Ymd');
        $prefix = "DIST-{$dateStr}-";
        $last = $this->fetch("SELECT disbursement_code FROM disbursements WHERE disbursement_code LIKE :pref ORDER BY id DESC LIMIT 1", [
            ':pref' => "{$prefix}%"
        ]);

        if ($last) {
            $num = (int)substr($last['disbursement_code'], -4);
            $next = str_pad((string)($num + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $next = '0001';
        }

        return "{$prefix}{$next}";
    }

    public function getAll(): array {
        $sql = "SELECT d.*, c.title AS campaign_title, b.name AS beneficiary_name, u.name AS recorded_by_name 
                FROM disbursements d
                JOIN campaigns c ON d.campaign_id = c.id
                LEFT JOIN beneficiaries b ON d.beneficiary_id = b.id
                JOIN users u ON d.recorded_by = u.id
                ORDER BY d.disbursement_date DESC, d.id DESC";
        return $this->fetchAll($sql);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT d.*, c.title AS campaign_title, c.slug AS campaign_slug, b.name AS beneficiary_name, 
                       b.address AS beneficiary_address, b.category AS beneficiary_category, u.name AS recorded_by_name 
                FROM disbursements d
                JOIN campaigns c ON d.campaign_id = c.id
                LEFT JOIN beneficiaries b ON d.beneficiary_id = b.id
                JOIN users u ON d.recorded_by = u.id
                WHERE d.id = :id LIMIT 1";
        return $this->fetch($sql, [':id' => $id]);
    }

    public function createDisbursement(array $data): int {
        return $this->insert($this->table, [
            'disbursement_code' => $data['disbursement_code'],
            'campaign_id' => $data['campaign_id'],
            'beneficiary_id' => !empty($data['beneficiary_id']) ? $data['beneficiary_id'] : null,
            'title' => $data['title'],
            'assistance_type' => $data['assistance_type'],
            'amount' => $data['amount'],
            'recipient_count' => $data['recipient_count'] ?? 1,
            'disbursement_date' => $data['disbursement_date'],
            'documentation_image' => $data['documentation_image'] ?? null,
            'receipt_file' => $data['receipt_file'] ?? null,
            'notes' => $data['notes'] ?? null,
            'recorded_by' => $data['recorded_by'],
        ]);
    }

    public function getTotalDisbursedAmount(): float {
        $val = $this->fetchColumn("SELECT SUM(amount) FROM disbursements");
        return (float)($val ?: 0);
    }

    public function getRecent(int $limit = 6): array {
        $sql = "SELECT d.*, c.title AS campaign_title, b.name AS beneficiary_name 
                FROM disbursements d
                JOIN campaigns c ON d.campaign_id = c.id
                LEFT JOIN beneficiaries b ON d.beneficiary_id = b.id
                ORDER BY d.disbursement_date DESC, d.id DESC LIMIT " . (int)$limit;
        return $this->fetchAll($sql);
    }

    public function getMonthlyStats(): array {
        $sql = "SELECT DATE_FORMAT(disbursement_date, '%Y-%m') AS month_label, SUM(amount) AS total
                FROM disbursements
                GROUP BY month_label
                ORDER BY month_label ASC
                LIMIT 12";
        return $this->fetchAll($sql);
    }
}
