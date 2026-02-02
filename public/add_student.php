<?php
require __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

requireLogin();
requireManager();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = 'user'; // force student

    if ($full_name === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } else {
        // check duplicate email
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = "This email is already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (full_name, email, password, role)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$full_name, $email, $hash, $role]);

            header("Location: index.php");
            exit;
        }
    }
}
?>

<?php require "../partials/header.php"; ?>

<form class="form" method="POST">
    <h3>Add Student</h3>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <input name="full_name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Temporary Password" required>

    <button type="submit">Create Student</button>
</form>

<?php require "../partials/footer.php"; ?>
