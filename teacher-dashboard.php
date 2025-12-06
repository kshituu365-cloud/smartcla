<?php
session_start();
require 'db.php';

// Ensure only teacher can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image:url('https://png.pngtree.com/thumb_back/fh260/background/20231203/pngtree-delicate-pastel-green-watercolor-texture-image_13824343.png');background-size:2000px;">

    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (Teacher)</h2>
        <center><br>
        <div class="dashboard-options">
            <a href="add_subject.php" class="btn">➕ Add Subject</a>
                        <br><br>
                                    <br><br>

            <a href="add_test.php" class="btn">📝 Add Test</a>
                        <br><br>            <br><br>


            <a href="add-questions.php" class="btn">❓ Add Question</a>
            <br><br>            <br><br>

            <a href="upload_materials.php" class="btn">📂 Upload Materials</a>
                        <br><br>
            <br><br>

            <a href="student_results.php" class="btn">📊 View Student Results</a>
        </div>
    </div>
</body>
</html>
