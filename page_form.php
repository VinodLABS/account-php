<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

requireAuth();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$page = [
    'id' => 0,
    'title' => '',
    'slug' => '',
    'meta_title' => '',
    'meta_description' => '',
    'status' => 'draft',
];

if ($id > 0) {
    $stmt = db()->prepare('SELECT * FROM pages WHERE id = :id AND deleted_at IS NULL LIMIT 1');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if ($found) {
        $page = $found;
    }
}

if (($_GET['section_action'] ?? '') === 'delete' && isset($_GET['section_id']) && $id > 0) {
    $stmt = db()->prepare('DELETE FROM sections WHERE id = :id AND page_id = :page_id');
    $stmt->execute([
        'id' => (int) $_GET['section_id'],
        'page_id' => $id,
    ]);
    header('Location: page_form.php?id=' . $id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['section_submit']) && $id > 0) {
    $sectionId = (int) ($_POST['section_id'] ?? 0);
    $sectionTitle = trim((string) ($_POST['section_title'] ?? ''));
    $content = (string) ($_POST['content'] ?? '');
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);

    if ($sectionId > 0) {
        $stmt = db()->prepare('UPDATE sections SET section_title=:section_title, content=:content, sort_order=:sort_order WHERE id=:id AND page_id=:page_id');
        $stmt->execute([
            'section_title' => $sectionTitle,
            'content' => $content,
            'sort_order' => $sortOrder,
            'id' => $sectionId,
            'page_id' => $id,
        ]);
    } else {
        $stmt = db()->prepare('INSERT INTO sections (page_id, section_title, content, sort_order) VALUES (:page_id, :section_title, :content, :sort_order)');
        $stmt->execute([
            'page_id' => $id,
            'section_title' => $sectionTitle,
            'content' => $content,
            'sort_order' => $sortOrder,
        ]);
    }

    header('Location: page_form.php?id=' . $id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['section_submit'])) {
    $title = trim((string) ($_POST['title'] ?? ''));
    $slug = slugify((string) ($_POST['slug'] ?? ''));
    if ($slug === '') {
        $slug = slugify($title);
    }

    $metaTitle = trim((string) ($_POST['meta_title'] ?? ''));
    $metaDescription = trim((string) ($_POST['meta_description'] ?? ''));
    $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';

    if ($id > 0) {
        $stmt = db()->prepare('UPDATE pages SET title=:title, slug=:slug, meta_title=:meta_title, meta_description=:meta_description, status=:status WHERE id=:id');
        $stmt->execute([
            'title' => $title,
            'slug' => $slug,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'status' => $status,
            'id' => $id,
        ]);
    } else {
        $stmt = db()->prepare('INSERT INTO pages (title, slug, meta_title, meta_description, status) VALUES (:title, :slug, :meta_title, :meta_description, :status)');
        $stmt->execute([
            'title' => $title,
            'slug' => $slug,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'status' => $status,
        ]);
        $id = (int) db()->lastInsertId();
    }

    header('Location: page_form.php?id=' . $id);
    exit;
}

$sectionEdit = ['id' => 0, 'section_title' => '', 'content' => '', 'sort_order' => 0];
if (isset($_GET['section_id']) && $id > 0) {
    $stmt = db()->prepare('SELECT * FROM sections WHERE id = :id AND page_id = :page_id LIMIT 1');
    $stmt->execute([
        'id' => (int) $_GET['section_id'],
        'page_id' => $id,
    ]);
    $foundSection = $stmt->fetch();
    if ($foundSection) {
        $sectionEdit = $foundSection;
    }
}

$sections = [];
if ($id > 0) {
    $stmt = db()->prepare('SELECT * FROM sections WHERE page_id = :page_id ORDER BY sort_order ASC, id ASC');
    $stmt->execute(['page_id' => $id]);
    $sections = $stmt->fetchAll();
}

layoutHeader($id > 0 ? 'Edit Page' : 'Create Page');
?>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        height: 250,
        menubar: false,
        plugins: 'link lists code table',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link table | code'
    });
</script>
<div class="topnav">
    <a href="admin.php">Back to Pages</a>
    <a href="logout.php">Logout</a>
</div>
<h1><?= $id > 0 ? 'Edit Page' : 'Create Page' ?></h1>
<form method="post">
    <div class="grid">
        <div>
            <label>Page Title</label>
            <input type="text" name="title" value="<?= h((string) $page['title']) ?>" required>
        </div>
        <div>
            <label>URL Slug</label>
            <input type="text" name="slug" value="<?= h((string) $page['slug']) ?>" placeholder="about-us" required>
        </div>
    </div>

    <label>Meta Title</label>
    <input type="text" name="meta_title" value="<?= h((string) $page['meta_title']) ?>">

    <label>Meta Description</label>
    <textarea name="meta_description" rows="3"><?= h((string) $page['meta_description']) ?></textarea>

    <label>Status</label>
    <select name="status">
        <option value="draft" <?= $page['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
        <option value="published" <?= $page['status'] === 'published' ? 'selected' : '' ?>>Published</option>
    </select>

    <p><button type="submit" class="btn">Save Page</button></p>
</form>

<?php if ($id > 0): ?>
    <h2>Sections</h2>
    <form method="post">
        <input type="hidden" name="section_submit" value="1">
        <input type="hidden" name="section_id" value="<?= (int) $sectionEdit['id'] ?>">

        <label>Section Title</label>
        <input type="text" name="section_title" value="<?= h((string) $sectionEdit['section_title']) ?>" required>

        <label>Section Content</label>
        <textarea id="content" name="content"><?= h((string) $sectionEdit['content']) ?></textarea>

        <label>Sort Order</label>
        <input type="number" name="sort_order" value="<?= (int) $sectionEdit['sort_order'] ?>">

        <p><button type="submit" class="btn"><?= (int) $sectionEdit['id'] > 0 ? 'Update Section' : 'Add Section' ?></button></p>
    </form>

    <?php foreach ($sections as $section): ?>
        <div class="card">
            <strong><?= h($section['section_title']) ?></strong>
            <div class="muted">Sort: <?= (int) $section['sort_order'] ?></div>
            <p>
                <a href="page_form.php?id=<?= $id ?>&section_id=<?= (int) $section['id'] ?>">Edit</a> |
                <a href="page_form.php?id=<?= $id ?>&section_action=delete&section_id=<?= (int) $section['id'] ?>" onclick="return confirm('Delete section?')">Delete</a>
            </p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php layoutFooter(); ?>
