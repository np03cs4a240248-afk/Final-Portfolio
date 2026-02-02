<?php
require __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

/* ---------------- CSRF TOKEN SETUP ---------------- */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
/* ------------------------------------------------- */

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* ---------- CSRF VALIDATION ---------- */
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die("Invalid CSRF token");
    }
    /* ------------------------------------ */

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("
        SELECT id, full_name, email, password, role
        FROM users
        WHERE email = ?
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error = "Invalid login credentials";
    } elseif (!password_verify($password, $user['password'])) {
        $error = "Invalid login credentials";
    } else {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['full_name'],
            'role' => $user['role']
        ];

        // Optional: regenerate token after successful login
        unset($_SESSION['csrf_token']);

        header("Location: index.php");
        exit;
    }
}
?>

<?php require "../partials/header.php"; ?>

<form class="form" method="POST">
    <h3>Login</h3>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- CSRF TOKEN -->
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Login</button>
</form>

<?php require "../partials/footer.php"; ?>
