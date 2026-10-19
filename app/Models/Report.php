<?php
// app/Models/Report.php
namespace App\Models;

use App\Core\Model;

class Report extends Model
{
    /** Total donasi terverifikasi per bulan. */
    public function totalDonationsByMonth(): array
    {
        return $this->fetchAll(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month,
                    SUM(amount) AS total,
                    COUNT(*) AS count
             FROM donations
             WHERE payment_status = 'verified'
             GROUP BY month
             ORDER BY month DESC"
        );
    }

    /** Total penyaluran per campaign. */
    public function disbursementByCampaign(): array
    {
        return $this->fetchAll(
            'SELECT c.title, SUM(d.amount) AS total, COUNT(d.id) AS count
             FROM disbursements d
             JOIN campaigns c ON c.id = d.campaign_id
             GROUP BY d.campaign_id, c.title
             ORDER BY total DESC'
        );
    }

    /** Ringkasan keuangan. */
    public function summary(): array
    {
        $income = (float) $this->scalar(
            "SELECT COALESCE(SUM(amount),0) FROM donations WHERE payment_status = 'verified'"
        );
        $expense = (float) $this->scalar('SELECT COALESCE(SUM(amount),0) FROM disbursements');
        $pending = (float) $this->scalar(
            "SELECT COALESCE(SUM(amount),0) FROM donations WHERE payment_status = 'pending'"
        );
        return [
            'income'  => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'pending' => $pending,
        ];
    }
}
