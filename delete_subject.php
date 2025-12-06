<?php
require 'db.php';

// Only teachers can access
if ($_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $subject_id = intval($_GET['id']);

    // Only delete subject created by the logged-in teacher
    $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ? AND teacher_id = ?");
    $stmt->bind_param("ii", $subject_id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $message = "Subject deleted successfully!";
    } else {
        $message = "Error deleting subject.";
    }
    $stmt->close();

    header("Location: view_subjects.php?msg=" . urlencode($message));
    exit();
} else {
    header("Location: view_subjects.php?msg=" . urlencode("Invalid subject ID."));
    exit();
}
