<?php
// app/Controllers/ReportController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Report;

class ReportController extends Controller
{
    protected string $layout = 'admin';

    public function financial(): void
    {
        $report = new Report();
        $summary = $report->summary();

        $this->render('admin/reports', [
            'title'      => 'Laporan Keuangan',
            'summary'    => $summary,
            'donations'  => $report->totalDonationsByMonth(),
            'disburse'   => $report->disbursementByCampaign(),
        ]);
    }

    public function exportCsv(): void
    {
        $report = new Report();
        $data   = $report->totalDonationsByMonth();
        $summary = $report->summary();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=donasi_per_bulan_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Bulan', 'Total Donasi', 'Jumlah Transaksi']);
        foreach ($data as $row) {
            fputcsv($output, [
                $row['month'],
                number_format((float) $row['total'], 0, ',', '.'),
                (int) $row['count'],
            ]);
        }
        fputcsv($output, []);
        fputcsv($output, ['Ringkasan', 'Nominal']);
        fputcsv($output, ['Total Pemasukan', number_format($summary['income'], 0, ',', '.')]);
        fputcsv($output, ['Total Penyaluran', number_format($summary['expense'], 0, ',', '.')]);
        fputcsv($output, ['Saldo', number_format($summary['balance'], 0, ',', '.')]);
        fputcsv($output, ['Donasi Pending', number_format($summary['pending'], 0, ',', '.')]);
        fclose($output);
        exit;
    }
}
