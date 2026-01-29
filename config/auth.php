<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* CHECK LOGIN */
function isLoggedIn() {
    return isset($_SESSION['user']);
}

/* REQUIRE LOGIN */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

/* CHECK MANAGER */
function isManager() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'manager';
}

/* REQUIRE MANAGER */
function requireManager() {
    if (!isManager()) {
        header("Location: index.php");
        exit;
    }
}
