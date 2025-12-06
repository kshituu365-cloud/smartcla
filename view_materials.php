<?php
session_start();
require 'db.php';

// Only students
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Fetch subjects with uploaded materials
$materials = $conn->query("
    SELECT m.id, m.file_name, m.file_path, s.name as subject_name
    FROM materials m
    JOIN subjects s ON m.subject_id = s.id
    ORDER BY s.name, m.file_name
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Materials</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image:url('https://wallpapercave.com/wp/wp2590353.jpg');background-size:2000px;">

<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Study Materials</h2>

    <?php if ($materials->num_rows === 0): ?>
        <p>No materials uploaded yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Subject</th>
                <th>File Name</th>
                <th>Download / View</th>
            </tr>
            <?php while ($row = $materials->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                <td><?php echo htmlspecialchars($row['file_name']); ?></td>
                <td>
                    <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View / Download</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
