<?php
// app/Models/Donation.php
namespace App\Models;

use App\Core\Model;

class Donation extends Model
{
    public function all(array $filters = []): array
    {
        $sql = "SELECT d.*, c.title AS campaign_title
                FROM donations d
                LEFT JOIN campaigns c ON c.id = d.campaign_id
                WHERE 1 = 1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= ' AND d.payment_status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['campaign_id'])) {
            $sql .= ' AND d.campaign_id = ?';
            $params[] = (int) $filters['campaign_id'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (d.donor_name LIKE ? OR d.donor_email LIKE ? OR d.donation_code LIKE ?)';
            $like = '%' . $filters['search'] . '%';
            array_push($params, $like, $like, $like);
        }

        $sql .= ' ORDER BY d.created_at DESC';
        return $this->fetchAll($sql, $params);
    }

    public function byCampaign(int $campaignId, int $limit = 10): array
    {
        return $this->fetchAll(
            "SELECT donor_name, amount, doa_message, is_anonymous, created_at
             FROM donations
             WHERE campaign_id = ? AND payment_status = 'verified'
             ORDER BY created_at DESC LIMIT {$limit}",
            [$campaignId]
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM donations WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        $code = $data['donation_code'] ?? $this->generateCode();
        $sql = 'INSERT INTO donations
                    (donation_code, campaign_id, user_id, donor_name, donor_email, donor_phone, amount,
                     payment_method, payment_status, payment_proof, doa_message, is_anonymous, created_at)
                VALUES
                    (:code, :campaign_id, :user_id, :donor_name, :donor_email, :donor_phone, :amount,
                     :payment_method, :payment_status, :payment_proof, :doa_message, :is_anonymous, NOW())';
        $this->query($sql, [
            ':code'           => $code,
            ':campaign_id'    => (int) $data['campaign_id'],
            ':user_id'        => $data['user_id'] ?? null,
            ':donor_name'     => $data['donor_name'],
            ':donor_email'    => $data['donor_email'],
            ':donor_phone'    => $data['donor_phone'] ?? null,
            ':amount'         => (float) $data['amount'],
            ':payment_method' => $data['payment_method'],
            ':payment_status' => $data['payment_status'] ?? 'pending',
            ':payment_proof'  => $data['payment_proof'] ?? null,
            ':doa_message'    => $data['doa_message'] ?? null,
            ':is_anonymous'   => !empty($data['is_anonymous']) ? 1 : 0,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function generateCode(): string
    {
        return 'DON-' . date('Ym') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
    }

    /**
     * Verifikasi donasi + tambahkan ke collected_amount campaign (transaksi).
     */
    public function verify(int $id, int $adminId): bool
    {
        $this->pdo->beginTransaction();
        try {
            $donation = $this->fetchOne('SELECT * FROM donations WHERE id = ? FOR UPDATE', [$id]);
            if (!$donation) {
                $this->pdo->rollBack();
                return false;
            }
            if ($donation['payment_status'] === 'verified') {
                $this->pdo->commit();
                return true;
            }
            $this->query(
                "UPDATE donations SET payment_status = 'verified', verified_by = ?, verified_at = NOW(),
                        rejection_reason = NULL, updated_at = NOW() WHERE id = ?",
                [$adminId, $id]
            );
            $this->query(
                'UPDATE campaigns SET collected_amount = collected_amount + ? WHERE id = ?',
                [(float) $donation['amount'], (int) $donation['campaign_id']]
            );
            $this->pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Tolak donasi + kembalikan collected_amount bila sebelumnya sudah verified.
     */
    public function reject(int $id, int $adminId, string $reason): bool
    {
        $this->pdo->beginTransaction();
        try {
            $donation = $this->fetchOne('SELECT * FROM donations WHERE id = ? FOR UPDATE', [$id]);
            if (!$donation) {
                $this->pdo->rollBack();
                return false;
            }
            if ($donation['payment_status'] === 'verified') {
                $this->query(
                    'UPDATE campaigns SET collected_amount = GREATEST(collected_amount - ?, 0) WHERE id = ?',
                    [(float) $donation['amount'], (int) $donation['campaign_id']]
                );
            }
            $this->query(
                "UPDATE donations SET payment_status = 'rejected', verified_by = ?, verified_at = NOW(),
                        rejection_reason = ?, updated_at = NOW() WHERE id = ?",
                [$adminId, $reason, $id]
            );
            $this->pdo->commit();
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function totalVerified(): float
    {
        return (float) $this->scalar("SELECT COALESCE(SUM(amount),0) FROM donations WHERE payment_status = 'verified'");
    }

    public function countByStatus(): array
    {
        $rows = $this->fetchAll('SELECT payment_status, COUNT(*) AS total FROM donations GROUP BY payment_status');
        $out = ['pending' => 0, 'verified' => 0, 'rejected' => 0];
        foreach ($rows as $row) {
            $out[$row['payment_status']] = (int) $row['total'];
        }
        return $out;
    }

    public function total(): int
    {
        return (int) $this->scalar('SELECT COUNT(*) FROM donations');
    }

    public function recent(int $limit = 5): array
    {
        return $this->fetchAll(
            "SELECT d.*, c.title AS campaign_title
             FROM donations d LEFT JOIN campaigns c ON c.id = d.campaign_id
             ORDER BY d.created_at DESC LIMIT {$limit}"
        );
    }

    public function recentVerified(int $limit = 6): array
    {
        return $this->fetchAll(
            "SELECT d.donor_name, d.amount, d.doa_message, d.is_anonymous, d.created_at, c.title AS campaign_title
             FROM donations d LEFT JOIN campaigns c ON c.id = d.campaign_id
             WHERE d.payment_status = 'verified'
             ORDER BY d.created_at DESC LIMIT {$limit}"
        );
    }

    public function monthly(): array
    {
        return $this->fetchAll(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(amount) AS total, COUNT(*) AS count
             FROM donations WHERE payment_status = 'verified'
             GROUP BY month ORDER BY month ASC"
        );
    }
}
