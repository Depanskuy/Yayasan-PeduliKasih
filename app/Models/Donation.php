<?php
// app/Models/Donation.php
namespace App\Models;

use App\Core\Model;

class Donation extends Model {
    protected string $table = 'donations';

    public function generateDonationCode(): string {
        $dateStr = date('Ymd');
        $prefix = "DON-{$dateStr}-";
        $last = $this->fetch("SELECT donation_code FROM donations WHERE donation_code LIKE :pref ORDER BY id DESC LIMIT 1", [
            ':pref' => "{$prefix}%"
        ]);

        if ($last) {
            $num = (int)substr($last['donation_code'], -4);
            $next = str_pad((string)($num + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $next = '0001';
        }

        return "{$prefix}{$next}";
    }

    public function createDonation(array $data): int {
        return $this->insert($this->table, [
            'donation_code' => $data['donation_code'],
            'campaign_id' => $data['campaign_id'],
            'user_id' => $data['user_id'] ?? null,
            'donor_name' => $data['donor_name'],
            'donor_email' => $data['donor_email'],
            'donor_phone' => $data['donor_phone'] ?? null,
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'payment_status' => $data['payment_status'] ?? 'pending',
            'payment_proof' => $data['payment_proof'] ?? null,
            'doa_message' => $data['doa_message'] ?? null,
            'is_anonymous' => $data['is_anonymous'] ?? 0,
        ]);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT d.*, c.title AS campaign_title, c.slug AS campaign_slug, u.name AS verified_by_name 
                FROM donations d
                JOIN campaigns c ON d.campaign_id = c.id
                LEFT JOIN users u ON d.verified_by = u.id
                WHERE d.id = :id LIMIT 1";
        return $this->fetch($sql, [':id' => $id]);
    }

    public function findByCode(string $code): ?array {
        $sql = "SELECT d.*, c.title AS campaign_title, c.slug AS campaign_slug 
                FROM donations d
                JOIN campaigns c ON d.campaign_id = c.id
                WHERE d.donation_code = :code LIMIT 1";
        return $this->fetch($sql, [':code' => $code]);
    }

    public function getAllForAdmin(?string $status = null): array {
        $sql = "SELECT d.*, c.title AS campaign_title, u.name AS verified_by_name 
                FROM donations d
                JOIN campaigns c ON d.campaign_id = c.id
                LEFT JOIN users u ON d.verified_by = u.id";
        $params = [];
        if ($status) {
            $sql .= " WHERE d.payment_status = :status";
            $params[':status'] = $status;
        }
        $sql .= " ORDER BY d.id DESC";
        return $this->fetchAll($sql, $params);
    }

    public function getUserDonations(int $userId): array {
        $sql = "SELECT d.*, c.title AS campaign_title, c.slug AS campaign_slug 
                FROM donations d
                JOIN campaigns c ON d.campaign_id = c.id
                WHERE d.user_id = :uid
                ORDER BY d.id DESC";
        return $this->fetchAll($sql, [':uid' => $userId]);
    }

    public function verifyDonation(int $id, int $verifiedBy): bool {
        $donation = $this->findById($id);
        if (!$donation || $donation['payment_status'] === 'verified') {
            return false;
        }

        $this->beginTransaction();
        try {
            $this->update($this->table, $id, [
                'payment_status' => 'verified',
                'verified_by' => $verifiedBy,
                'verified_at' => date('Y-m-d H:i:s'),
                'rejection_reason' => null
            ]);

            // Add collected amount to campaign
            $campaignModel = new Campaign();
            $campaignModel->addCollectedAmount($donation['campaign_id'], (float)$donation['amount']);

            $this->commit();
            return true;
        } catch (\Throwable $e) {
            $this->rollBack();
            return false;
        }
    }

    public function rejectDonation(int $id, int $verifiedBy, string $reason): bool {
        return $this->update($this->table, $id, [
            'payment_status' => 'rejected',
            'verified_by' => $verifiedBy,
            'verified_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason
        ]);
    }

    public function getTotalVerifiedAmount(): float {
        $val = $this->fetchColumn("SELECT SUM(amount) FROM donations WHERE payment_status = 'verified'");
        return (float)($val ?: 0);
    }

    public function getTotalVerifiedCount(): int {
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM donations WHERE payment_status = 'verified'");
    }

    public function getMonthlyStats(): array {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month_label, SUM(amount) AS total
                FROM donations
                WHERE payment_status = 'verified'
                GROUP BY month_label
                ORDER BY month_label ASC
                LIMIT 12";
        return $this->fetchAll($sql);
    }
}
