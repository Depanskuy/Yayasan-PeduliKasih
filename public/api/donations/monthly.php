<?php
require __DIR__.'/../../config/database.php';   // PDO instance
require __DIR__.'/../../app/Models/Report.php';

$report = new \App\Models\Report();
header('Content-Type: application/json');
echo json_encode($report->totalDonationsByMonth());