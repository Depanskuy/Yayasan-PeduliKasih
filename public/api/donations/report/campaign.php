<?php
require __DIR__.'/../../config/database.php';
require __DIR__.'/../../app/Models/Report.php';

$rep = new \App\Models\Report();
header('Content-Type: application/json');
echo json_encode($rep->disbursementByCampaign());