<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $test_id = intval($_GET['id']);
    $sql = "DELETE FROM tests WHERE id = $test_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Test deleted successfully'); window.location.href='add_test.php';</script>";
    } else {
        echo "<script>alert('Error deleting test'); window.location.href='add_test.php';</script>";
    }
} else {
    header("Location: add_test.php");
    exit();
}
?>
