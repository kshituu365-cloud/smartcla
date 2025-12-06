<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    die("❌ You must be logged in to view your results");
}

$student_id = $_SESSION['user_id'];

// Fetch results from 'results' table including username
$sql = "SELECT r.score, r.created_at, 
               t.name AS test_name, 
               s.name AS subject_name, 
               u.username
        FROM results r
        JOIN tests t ON r.test_id = t.id
        JOIN subjects s ON t.subject_id = s.id
        JOIN users u ON r.student_id = u.id
        WHERE r.student_id = ?
        ORDER BY r.created_at DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) die("SQL Error: " . $conn->error);

$stmt->bind_param("i", $student_id);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image:url('https://tse2.mm.bing.net/th/id/OIP.G3tJfv_A5AieXnYqUeJ7DgHaEK?rs=1&pid=ImgDetMain&o=7&rm=3');background-size:2000px;">

    <h2>📊 My Test Results</h2>

    <table border="1" cellpadding="8">
        <tr>
            <th>Username</th>
            <th>Test Name</th>
            <th>Subject</th>
            <th>Score</th>
            <th>Date</th>
        </tr>

        <?php while($row = $results->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['test_name']); ?></td>
            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
            <td><?php echo $row['score']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
