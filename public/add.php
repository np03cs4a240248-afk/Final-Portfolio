<?php
require __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";
requireLogin();
if (!isManager()) die("Access denied");

if ($_POST) {
    $stmt = $conn->prepare(
        "INSERT INTO books (title, author, genre, published_year, isbn)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "sssis",
        $_POST['title'],
        $_POST['author'],
        $_POST['genre'],
        $_POST['year'],
        $_POST['isbn']
    );
    $stmt->execute();
    header("Location: index.php");
}
?>

<?php require "../partials/header.php"; ?>

<form class="form" method="POST">
<input name="title" placeholder="Title" required>
<input name="author" placeholder="Author" required>
<input name="genre" placeholder="Genre" required>
<input type="number" name="year" placeholder="Published Year" required>
<input name="isbn" placeholder="ISBN">
<button>Add Book</button>
</form>

<?php require "../partials/footer.php"; ?>
