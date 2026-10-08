<?php
// test_system.php
$cookieFile = tempnam(sys_get_temp_dir(), 'cook');

// 1. Get login page to grab CSRF token
$ch = curl_init('http://127.0.0.1:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$html = curl_exec($ch);
curl_close($ch);

preg_match('/name="_csrf_token" value="([^"]+)"/', $html, $matches);
$csrf = $matches[1] ?? '';
echo "CSRF token acquired: " . substr($csrf, 0, 10) . "...\n";

// 2. Perform POST login as Super Admin
$ch = curl_init('http://127.0.0.1:8000/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_csrf_token' => $csrf,
    'email' => 'admin@pedulikasih.test',
    'password' => 'password'
]));
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Login status: {$httpCode}, Redirected to: {$effectiveUrl}\n";

// 3. Request Admin Dashboard, Donations, Beneficiaries, Distributions, Audit Logs
$adminEndpoints = [
    'http://127.0.0.1:8000/admin/dashboard',
    'http://127.0.0.1:8000/admin/campaigns',
    'http://127.0.0.1:8000/admin/donations',
    'http://127.0.0.1:8000/admin/donations/offline',
    'http://127.0.0.1:8000/admin/beneficiaries',
    'http://127.0.0.1:8000/admin/distributions',
    'http://127.0.0.1:8000/admin/volunteers/events',
    'http://127.0.0.1:8000/admin/volunteers/reports',
    'http://127.0.0.1:8000/admin/articles',
    'http://127.0.0.1:8000/admin/gallery',
    'http://127.0.0.1:8000/admin/audit-logs',
    'http://127.0.0.1:8000/admin/users',
    'http://127.0.0.1:8000/admin/settings',
    'http://127.0.0.1:8000/transparency/report/print'
];

foreach ($adminEndpoints as $ep) {
    $ch = curl_init($ep);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "{$ep} => Status {$code} (Length: " . strlen($content) . ")\n";
}

// 4. Test Receipt & Certificate for an existing verified donation
$ch = curl_init('http://127.0.0.1:8000/donation/receipt/DON-202603-001');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Receipt DON-202603-001 => Status {$code} (Length: " . strlen($content) . ")\n";

$ch = curl_init('http://127.0.0.1:8000/donation/certificate/DON-202603-001');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Certificate DON-202603-001 => Status {$code} (Length: " . strlen($content) . ")\n";

@unlink($cookieFile);
echo "System verification test completed.\n";
