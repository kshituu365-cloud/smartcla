<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Set role
$role = $_SESSION['role'];
?>
<style>
    nav { background: #007BFF; padding: 10px; color: #fff; }
    nav a { color: #fff; margin-right: 15px; text-decoration: none; font-weight: bold; }
    nav a:hover { text-decoration: underline; }
</style>

<nav>
    <a href="login.php">Home</a>

    <?php if ($role === 'teacher'): ?>
        <a href="view_subjects.php">Subjects</a>
        <a href="add_test.php">Add Test</a>
        <a href="add-questions.php">Add Questions</a>
        <a href="upload_materials.php">Upload Material</a>
        <a href="student_results.php">Student Results</a>
    <?php elseif ($role === 'student'): ?>
        <a href="view_materials.php">Study Materials</a>
        <a href="view_results.php">My Results</a>
    <?php endif; ?>

    <a href="logout.php" style="float:right;">Logout</a>
</nav>
