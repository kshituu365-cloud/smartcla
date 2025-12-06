<?php
require 'db.php';

// Only teachers
if ($_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

// Fetch teacher's subjects
$stmt = $conn->prepare("SELECT id, name FROM subjects WHERE teacher_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$subjects = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $test_name  = trim($_POST['test_name']);

    if (!empty($subject_id) && !empty($test_name)) {
        $stmt = $conn->prepare("INSERT INTO tests (subject_id, name) VALUES (?, ?)");
        $stmt->bind_param("is", $subject_id, $test_name);
        $stmt->execute();
        $stmt->close();
        $success = "Test added successfully!";
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Test</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image:url('https://wallpapercave.com/wp/wp2590353.jpg');background-size:2000px;">

<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Add New Test</h2>
    <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        <label>Choose Subject:</label><br>
        <select name="subject_id" required>
            <option value="">--Select--</option>
            <?php while($row = $subjects->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Test Name:</label><br>
        <input type="text" name="test_name" required><br><br>

        <button type="submit">Add Test</button>
    </form>
</div>
</body>
</html>
