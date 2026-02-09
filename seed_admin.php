<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$email = 'admin@example.com';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = db()->prepare('INSERT INTO admins (email, password_hash) VALUES (:email, :password_hash) ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)');
$stmt->execute([
    'email' => $email,
    'password_hash' => $hash,
]);

echo "Seeded admin user\nEmail: {$email}\nPassword: {$password}\n";
