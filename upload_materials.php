<?php
session_start();
require 'db.php';

// Only teachers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Fetch teacher's subjects
$stmt = $conn->prepare("SELECT id, name FROM subjects WHERE teacher_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$subjects = $stmt->get_result();

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $file_name  = $_FILES['file']['name'];
    $tmp_name   = $_FILES['file']['tmp_name'];
    $upload_dir = 'uploads/';
    $file_path  = $upload_dir . basename($file_name);

    if (move_uploaded_file($tmp_name, $file_path)) {
        $stmt = $conn->prepare("INSERT INTO materials (subject_id, file_name, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $subject_id, $file_name, $file_path);
        if ($stmt->execute()) {
            $success = "File uploaded successfully!";
        } else {
            $error = "Database error: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "Failed to upload file.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Material</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image:url('https://wallpapercave.com/wp/wp2590353.jpg');background-size:2000px;">

<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Upload Study Material</h2>

    <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Select Subject:</label><br>
        <select name="subject_id" required>
            <option value="">--Select--</option>
            <?php while($row = $subjects->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Select File (PDF/Image):</label><br>
        <input type="file" name="file" accept=".pdf,image/*" required><br><br>

        <button type="submit">Upload</button>
    </form>
</div>
</body>
</html>
