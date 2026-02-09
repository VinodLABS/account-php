<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

requireAuth();

if (($_GET['action'] ?? '') === 'delete' && isset($_GET['id'])) {
    $stmt = db()->prepare('UPDATE pages SET deleted_at = NOW() WHERE id = :id');
    $stmt->execute(['id' => (int) $_GET['id']]);
    header('Location: admin.php');
    exit;
}

$pages = db()->query('SELECT id, title, slug, status, updated_at FROM pages WHERE deleted_at IS NULL ORDER BY updated_at DESC')->fetchAll();

layoutHeader('Admin - Pages');
?>
<div class="topnav">
    <a href="admin.php">Pages</a>
    <a href="page_form.php">Create Page</a>
    <a href="logout.php">Logout</a>
</div>
<h1>Pages</h1>
<table>
    <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($pages as $page): ?>
        <tr>
            <td><?= h($page['title']) ?></td>
            <td>/<?= h($page['slug']) ?></td>
            <td><?= h($page['status']) ?></td>
            <td><?= h($page['updated_at']) ?></td>
            <td>
                <a href="page_form.php?id=<?= (int) $page['id'] ?>">Edit</a> |
                <a href="index.php?slug=<?= urlencode($page['slug']) ?>" target="_blank">View</a> |
                <a href="admin.php?action=delete&id=<?= (int) $page['id'] ?>" onclick="return confirm('Soft delete this page?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php layoutFooter(); ?>
