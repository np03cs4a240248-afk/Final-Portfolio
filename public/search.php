<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

$q = trim($_GET['q'] ?? '');

$sql = "
SELECT 
    books.id,
    books.title,
    books.published_year,
    books.isbn,
    authors.name AS author,
    categories.name AS genre
FROM books
JOIN authors ON books.author_id = authors.id
JOIN categories ON books.category_id = categories.id
";

$params = [];

if ($q !== '') {
    $sql .= "
        WHERE books.title LIKE ?
        OR authors.name LIKE ?
        OR categories.name LIKE ?
        OR books.published_year LIKE ?
    ";
    $params = ["%$q%", "%$q%", "%$q%", "%$q%"];
}

$sql .= " ORDER BY books.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();

/* OUTPUT TABLE ROWS ONLY */
foreach ($books as $row): ?>
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
