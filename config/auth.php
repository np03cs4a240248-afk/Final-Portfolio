<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function isManager() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'manager';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function requireManager() {
    if (!isManager()) {
        die("Access denied");
    }
}
