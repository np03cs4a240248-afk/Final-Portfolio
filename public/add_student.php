<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

/* ONLY MANAGER CAN ACCESS */
requireManager();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if ($name && $email && $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (full_name, email, password, role)
            VALUES (?, ?, ?, 'student')
        ");
        $stmt->execute([$name, $email, $hashed]);

        $message = "Student account created successfully.";
    } else {
        $message = "All fields are required.";
    }
}

require "../partials/header.php";
?>

<div class="container">

    <form class="form" method="post">
        <h3>Add Student</h3>

        <?php if ($message): ?>
            <div class="success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <input type="text" name="name" placeholder="Student Full Name" required>
        <input type="email" name="email" placeholder="Student Email" required>
        <input type="password" name="password" placeholder="Temporary Password" required>

        <button type="submit">Create Student</button>
    </form>

</div>

<?php require "../partials/footer.php"; ?>
