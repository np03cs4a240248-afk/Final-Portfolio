<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    /* BASIC VALIDATION */
    if ($name === "" || $email === "" || $password === "") {
        $error = "All fields are required";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match";
    } else {

        /* CHECK IF EMAIL EXISTS */
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Email already registered";
        } else {

            /* HASH PASSWORD */
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            /* INSERT USER (ROLE = user) */
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password, role)
                 VALUES (?, ?, ?, 'user')"
            );

            $stmt->execute([
                $name,
                $email,
                $hashedPassword
            ]);

            $success = "Account created successfully. You can now login.";
        }
    }
}
?>

<?php require "../partials/header.php"; ?>

<form class="form" method="POST">
    <h3>Sign Up</h3>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>

    <button type="submit">Sign Up</button>

    <p class="small">
        Already have an account? <a href="login.php">Login</a>
    </p>
</form>

<?php require "../partials/footer.php"; ?>
