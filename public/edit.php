<?php
require __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

requireLogin();
requireManager();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) die("Invalid book id");

$authors = $pdo->query("SELECT id, name FROM authors ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) die("Book not found");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title'] ?? '');
    $author_id = (int)($_POST['author_id'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $year = (int)($_POST['year'] ?? 0);
    $isbn = trim($_POST['isbn'] ?? '');

    if ($title === '' || $author_id <= 0 || $category_id <= 0 || $year <= 0) {
        $error = "Please fill all required fields.";
    } else {
        $upd = $pdo->prepare("
            UPDATE books
            SET title = ?, author_id = ?, category_id = ?, published_year = ?, isbn = ?
            WHERE id = ?
        ");
        $upd->execute([$title, $author_id, $category_id, $year, ($isbn === '' ? null : $isbn), $id]);

        header("Location: index.php");
        exit;
    }
}
?>

<?php require "../partials/header.php"; ?>

<form class="form" method="POST">
    <h3>Edit Book</h3>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <input name="title" value="<?= htmlspecialchars($book['title']) ?>" required>

    <select name="author_id" required>
        <?php foreach ($authors as $a): ?>
            <option value="<?= (int)$a['id'] ?>" <?= ((int)$book['author_id'] === (int)$a['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($a['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="category_id" required>
        <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= ((int)$book['category_id'] === (int)$c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="number" name="year" value="<?= (int)$book['published_year'] ?>" required>
    <input name="isbn" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>" placeholder="ISBN (optional)">

    <button type="submit">Save Changes</button>
</form>

<?php require "../partials/footer.php"; ?>
