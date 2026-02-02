<?php
require __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

requireLogin();

$isManager = isManager();

$books = $pdo->query("
    SELECT
        b.id, b.title,
        a.name AS author,
        c.name AS category,
        b.published_year,
        b.isbn
    FROM books b
    JOIN authors a ON b.author_id = a.id
    JOIN categories c ON b.category_id = c.id
    ORDER BY b.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require "../partials/header.php"; ?>

<h2>Library Dashboard</h2>

<input id="searchBox" placeholder="Search by title, author, category, year..." style="width:100%;max-width:520px;padding:10px;">

<?php if ($isManager): ?>
  <div style="margin:12px 0; display:flex; gap:10px;">
      <a href="add.php"><button>+ Add Book</button></a>
      <a href="add_student.php"><button>+ Add Student</button></a>
  </div>
<?php endif; ?>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Year</th>
            <th>ISBN</th>
            <?php if ($isManager): ?><th>Actions</th><?php endif; ?>
        </tr>
    </thead>
    <tbody id="bookTableBody">
        <?php foreach ($books as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b['title']) ?></td>
                <td><?= htmlspecialchars($b['author']) ?></td>
                <td><?= htmlspecialchars($b['category']) ?></td>
                <td><?= (int)$b['published_year'] ?></td>
                <td><?= htmlspecialchars($b['isbn'] ?? '') ?></td>
                <?php if ($isManager): ?>
                    <td>
                        <a href="edit.php?id=<?= (int)$b['id'] ?>">Edit</a> |
                        <a href="delete.php?id=<?= (int)$b['id'] ?>" onclick="return confirm('Delete this book?')">Delete</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
const box = document.getElementById('searchBox');
const tbody = document.getElementById('bookTableBody');
const isManager = <?= $isManager ? 'true' : 'false' ?>;

let t = null;
box.addEventListener('input', () => {
  clearTimeout(t);
  t = setTimeout(async () => {
    const q = box.value.trim();
    const res = await fetch('search.php?q=' + encodeURIComponent(q));
    const data = await res.json();

    tbody.innerHTML = '';
    if (!data.length) {
      const tr = document.createElement('tr');
      tr.innerHTML = `<td colspan="${isManager ? 6 : 5}">No books found</td>`;
      tbody.appendChild(tr);
      return;
    }

    data.forEach(b => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${escapeHtml(b.title)}</td>
        <td>${escapeHtml(b.author)}</td>
        <td>${escapeHtml(b.category)}</td>
        <td>${b.published_year}</td>
        <td>${b.isbn ?? ''}</td>
        ${isManager ? `<td>
            <a href="edit.php?id=${b.id}">Edit</a> |
            <a href="delete.php?id=${b.id}" onclick="return confirm('Delete this book?')">Delete</a>
          </td>` : ''}
      `;
      tbody.appendChild(tr);
    });
  }, 250);
});

function escapeHtml(str) {
  return String(str ?? '').replace(/[&<>"']/g, (m) => ({
    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
  }[m]));
}
</script>

<?php require "../partials/footer.php"; ?>
