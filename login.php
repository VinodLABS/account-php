<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

if (isLoggedIn()) {
    header('Location: admin.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (login($email, $password)) {
        header('Location: admin.php');
        exit;
    }

    $error = 'Invalid credentials.';
}

layoutHeader('Admin Login');
?>
<h1>Admin Login</h1>
<?php if ($error !== ''): ?>
    <p style="color:#d73a49;"><?= h($error) ?></p>
<?php endif; ?>
<form method="post">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <p><button class="btn" type="submit">Login</button></p>
</form>
<p class="muted">Default admin can be created by running <code>php seed_admin.php</code>.</p>
<?php layoutFooter(); ?>
