<?php
session_start();
require 'db.php';

// Only teachers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Fetch results for tests under this teacher
$query = "
    SELECT r.id as result_id, u.username as student_name, s.name as subject_name, t.name as test_name, r.score
    FROM results r
    JOIN users u ON r.student_id = u.id
    JOIN tests t ON r.test_id = t.id
    JOIN subjects s ON t.subject_id = s.id
    WHERE s.teacher_id = ?
    ORDER BY s.name, t.name, u.username
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$results = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image:url('https://wallpapercave.com/wp/wp2590353.jpg');background-size:2000px;">

<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Student Results</h2>

    <?php if ($results->num_rows === 0): ?>
        <p>No results available yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Student Name</th>
                <th>Subject</th>
                <th>Test</th>
                <th>Score</th>
            </tr>
            <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                <td><?php echo htmlspecialchars($row['test_name']); ?></td>
                <td><?php echo $row['score']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
