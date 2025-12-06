<?php
require 'db.php';

// Only teachers can access
if ($_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO subjects (name, teacher_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $message = "Subject added successfully!";
        } else {
            $message = "Error adding subject.";
        }
        $stmt->close();
    } else {
        $message = "Subject name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Subject</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image:url('https://png.pngtree.com/thumb_back/fh260/background/20231203/pngtree-delicate-pastel-green-watercolor-texture-image_13824343.png');background-size:2000px;">

    <?php include 'navbar.php'; ?>
    <div class="container">
        <h2>Add Subject</h2>

        <?php if (!empty($message)) echo "<p>$message</p>"; ?>

        <form method="POST">
            <label>Subject Name:</label><br>
            <input type="text" name="name" required>
            <br><br>
            <button type="submit">Add Subject</button>
        </form>
    </div>
</body>
</html>
