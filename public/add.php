<?php
require __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

requireLogin();
requireManager();

$authors = $pdo->query("SELECT id, name FROM authors ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

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
        $stmt = $pdo->prepare("
            INSERT INTO books (title, author_id, category_id, published_year, isbn)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$title, $author_id, $category_id, $year, ($isbn === '' ? null : $isbn)]);

        header("Location: index.php");
        exit;
    }
}
?>

<?php require "../partials/header.php"; ?>

<form class="form" method="POST">
    <h3>Add Book</h3>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <input name="title" placeholder="Title" required>

    <select name="author_id" required>
        <option value="">Select author</option>
        <?php foreach ($authors as $a): ?>
            <option value="<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <select name="category_id" required>
        <option value="">Select category</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <input type="number" name="year" placeholder="Published year" required>
    <input name="isbn" placeholder="ISBN (optional)">

    <button type="submit">Add Book</button>
</form>

<?php require "../partials/footer.php"; ?>
