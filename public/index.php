<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

/* IF NOT LOGGED IN, REDIRECT TO LOGIN */
requireLogin();

/* FETCH BOOKS WITH AUTHOR + CATEGORY */
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
ORDER BY books.created_at DESC
";

$stmt = $pdo->query($sql);
$books = $stmt->fetchAll();

require "../partials/header.php";
?>
<div class="page-head">
  <div>
    <h1>Library Dashboard</h1>
    <p>Search books by title, author, category and year.</p>
  </div>

  <?php if (isManager()): ?>
    <div class="head-actions">
      <a class="btn" href="add.php">+ Add Book</a>
      <a class="btn btn-ghost" href="add_student.php">+ Add Student</a>
    </div>
  <?php endif; ?>
</div>

<div class="card">
  <div class="card-top">
    <input type="text" id="search" placeholder="Search by title, author, category, year...">
  </div>

  <table>
    <thead>...</thead>
    <tbody id="bookTable">...</tbody>
  </table>
</div>


<!-- LIVE SEARCH SCRIPT -->
<script>
document.getElementById("search").addEventListener("keyup", function () {
    const q = this.value.trim();

    fetch("search.php?q=" + encodeURIComponent(q))
        .then(res => res.text())
        .then(html => {
            document.getElementById("bookTable").innerHTML = html;
        });
});
</script>

<?php require "../partials/footer.php"; ?>
