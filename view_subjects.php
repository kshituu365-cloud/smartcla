<?php
require 'db.php';

// Only teachers can access
if ($_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

// Fetch teacher's subjects
$stmt = $conn->prepare("SELECT id, name FROM subjects WHERE teacher_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Subjects</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f4f4; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #007BFF; color: #fff; }
        a { text-decoration: none; color: #007BFF; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
        .delete-btn { background: #dc3545; color: #fff; }
        .add-btn { display: block; margin: 15px 0; text-align: center; padding: 8px; background: #28a745; color: #fff; border-radius: 4px; }
    </style>
</head>
<body style="background-image:url('https://wallpapercave.com/wp/wp2590353.jpg');background-size:2000px;">

<div class="container">
    <h2>My Subjects</h2>

    <?php if (isset($_GET['msg'])): ?>
        <p style="color: green; text-align: center;"><?php echo htmlspecialchars($_GET['msg']); ?></p>
    <?php endif; ?>

    <a class="add-btn" href="add_subject.php">+ Add New Subject</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Subject Name</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td>
                <a class="delete-btn btn" href="delete_subject.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this subject?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
