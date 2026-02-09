<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

$slug = slugify((string) ($_GET['slug'] ?? 'home'));

$stmt = db()->prepare('SELECT * FROM pages WHERE slug = :slug AND status = "published" AND deleted_at IS NULL LIMIT 1');
$stmt->execute(['slug' => $slug]);
$page = $stmt->fetch();

if (!$page) {
    http_response_code(404);
    layoutHeader('Page Not Found');
    echo '<h1>404 - Page Not Found</h1>';
    echo '<p><a href="index.php">Go to Home</a></p>';
    layoutFooter();
    exit;
}

$stmt = db()->prepare('SELECT section_title, content FROM sections WHERE page_id = :page_id ORDER BY sort_order ASC, id ASC');
$stmt->execute(['page_id' => $page['id']]);
$sections = $stmt->fetchAll();

layoutHeader($page['meta_title'] ?: $page['title']);
?>
<meta name="description" content="<?= h((string) $page['meta_description']) ?>">
<header>
    <h1><?= h($page['title']) ?></h1>
</header>
<?php foreach ($sections as $section): ?>
    <section>
        <h2><?= h($section['section_title']) ?></h2>
        <div><?= $section['content'] ?></div>
    </section>
<?php endforeach; ?>
<?php layoutFooter(); ?>
