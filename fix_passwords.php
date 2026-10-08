<?php
// fix_passwords.php
$pdo = require __DIR__ . '/config/database.php';
$hash = password_hash('password', PASSWORD_BCRYPT);

$stmt = $pdo->prepare("UPDATE users SET password = :hash");
$stmt->execute([':hash' => $hash]);

echo "All user passwords successfully reset to 'password' with hash: " . $hash . "\n";
