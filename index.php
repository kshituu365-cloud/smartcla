<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirect based on role
if ($_SESSION['role'] === 'teacher') {
    header("Location: view_subjects.php"); // Teacher dashboard
    exit();
} elseif ($_SESSION['role'] === 'student') {
    header("Location: take_test.php"); // Student dashboard
    exit();
} else {
    echo "Invalid role. Please contact admin.";
    exit();
}
