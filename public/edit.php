<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

requireLogin();
if (!isManager()) {
    die("Access denied");
}

/* VALIDATE ID */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request");
}

$id = (int) $_GET['id'];

/* FETCH BOOK */
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    die("Book not found");
}

/* UPDATE BOOK */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $sql = "UPDATE books
            SET title = ?, author = ?, genre = ?, published_year = ?, isbn = ?
            WHERE id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['title'],
        $_POST['author'],
        $_POST['genre'],
        $_POST['year'],
        $_POST['isbn'],
        $id
    ]);

    header("Location: index.php");
    exit;
}
?>

<?php require "../partials/header.php"; ?>

<form class="form" method="POST">
    <h3>Edit Book</h3>

    <input
        name="title"
        value="<?= htmlspecialchars($book['title']) ?>"
        required
    >

    <input
        name="author"
        value="<?= htmlspecialchars($book['author']) ?>"
        required
    >

    <input
        name="genre"
        value="<?= htmlspecialchars($book['genre']) ?>"
        required
    >

    <input
        type="number"
        name="year"
        value="<?= htmlspecialchars($book['published_year']) ?>"
        required
    >

    <input
        name="isbn"
        value="<?= htmlspecialchars($book['isbn']) ?>"
    >

    <button type="submit">Update Book</button>
</form>

<?php require "../partials/footer.php"; ?>
