<?php
// app/Models/Disbursement.php
namespace App\Models;

use App\Core\Model;

class Disbursement extends Model
{
    public function all(): array
    {
        return $this->fetchAll(
            "SELECT d.*, c.title AS campaign_title, b.name AS beneficiary_name, u.name AS recorded_by_name
             FROM disbursements d
             LEFT JOIN campaigns c ON c.id = d.campaign_id
             LEFT JOIN beneficiaries b ON b.id = d.beneficiary_id
             LEFT JOIN users u ON u.id = d.recorded_by
             ORDER BY d.disbursement_date DESC, d.id DESC"
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM disbursements WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO disbursements
                    (disbursement_code, campaign_id, beneficiary_id, title, assistance_type, amount,
                     recipient_count, disbursement_date, documentation_image, receipt_file, notes, recorded_by, created_at)
                VALUES
                    (:code, :campaign_id, :beneficiary_id, :title, :assistance_type, :amount,
                     :recipient_count, :disbursement_date, :documentation_image, :receipt_file, :notes, :recorded_by, NOW())';
        $this->query($sql, [
            ':code'                => $data['disbursement_code'] ?? $this->generateCode(),
            ':campaign_id'         => (int) $data['campaign_id'],
            ':beneficiary_id'      => !empty($data['beneficiary_id']) ? (int) $data['beneficiary_id'] : null,
            ':title'               => $data['title'],
            ':assistance_type'     => $data['assistance_type'],
            ':amount'              => (float) $data['amount'],
            ':recipient_count'     => (int) ($data['recipient_count'] ?? 1),
            ':disbursement_date'   => $data['disbursement_date'],
            ':documentation_image' => $data['documentation_image'] ?? null,
            ':receipt_file'        => $data['receipt_file'] ?? null,
            ':notes'               => $data['notes'] ?? null,
            ':recorded_by'         => (int) $data['recorded_by'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function generateCode(): string
    {
        return 'DIS-' . date('Ym') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
    }

    public function total(): float
    {
        return (float) $this->scalar('SELECT COALESCE(SUM(amount),0) FROM disbursements');
    }

    public function totalCount(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM disbursements');
    }

    public function recent(int $limit = 5): array
    {
        return $this->fetchAll(
            "SELECT d.*, c.title AS campaign_title
             FROM disbursements d LEFT JOIN campaigns c ON c.id = d.campaign_id
             ORDER BY d.disbursement_date DESC, d.id DESC LIMIT {$limit}"
        );
    }
}
