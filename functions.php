<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';

    return trim($text, '-');
}

function isLoggedIn(): bool
{
    return isset($_SESSION['admin_id']);
}

function requireAuth(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function login(string $email, string $password): bool
{
    $stmt = db()->prepare('SELECT id, password_hash FROM admins WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }

    $_SESSION['admin_id'] = (int) $admin['id'];

    return true;
}

function logout(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }

    session_destroy();
}

function layoutHeader(string $title): void
{
    echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . h($title) . '</title>';
    echo '<style>body{font-family:Arial,sans-serif;max-width:1000px;margin:20px auto;padding:0 12px}';
    echo 'label{display:block;margin:.7rem 0 .2rem}input,textarea,select{width:100%;padding:.5rem}';
    echo 'table{width:100%;border-collapse:collapse;margin-top:1rem}th,td{border:1px solid #ddd;padding:.5rem;text-align:left}';
    echo '.topnav a{margin-right:10px}.btn{display:inline-block;padding:.45rem .7rem;background:#0366d6;color:#fff;text-decoration:none;border:none;cursor:pointer}.btn-danger{background:#d73a49}.muted{color:#666}.card{border:1px solid #ddd;padding:12px;margin:10px 0}.grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}</style>';
    echo '</head><body>';
}

function layoutFooter(): void
{
    echo '</body></html>';
}
