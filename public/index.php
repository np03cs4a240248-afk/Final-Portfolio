<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

/* IF NOT LOGGED IN, SHOW SIGNUP FIRST */
if (!isLoggedIn()) {
    header("Location: signup.php");
    exit;
}
requireLogin();
require "../partials/header.php";

/* FETCH BOOKS USING PDO */
$stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
$books = $stmt->fetchAll();
?>

<div style="position: relative; max-width: 400px; margin: 20px auto;">
    <input type="text" id="search" placeholder="Search books..." autocomplete="off">
    <div id="results"></div>
</div>


<?php if (isManager()): ?>
    <a class="btn" href="add.php">Add Book</a>
<?php endif; ?>

<table>
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Genre</th>
    <th>Year</th>
    <th>ISBN</th>
    <?php if (isManager()): ?><th>Actions</th><?php endif; ?>
</tr>

<?php foreach ($books as $row): ?>
<tr>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><?= htmlspecialchars($row['author']) ?></td>
    <td><?= htmlspecialchars($row['genre']) ?></td>
    <td><?= htmlspecialchars($row['published_year']) ?></td>
    <td><?= htmlspecialchars($row['isbn']) ?></td>

    <?php if (isManager()): ?>
    <td>
        <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
        <a href="delete.php?id=<?= $row['id'] ?>"
           onclick="return confirm('Are you sure?')">Delete</a>
    </td>
    <?php endif; ?>
</tr>
<?php endforeach; ?>
</table>

<script src="../assets/script.js"></script>

<?php require "../partials/footer.php"; ?>
