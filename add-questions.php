<?php
require 'db.php';

// Only teachers
if ($_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

// Fetch teacher's tests
$stmt = $conn->prepare("
    SELECT t.id as test_id, t.name as test_name, s.name as subject_name 
    FROM tests t 
    JOIN subjects s ON t.subject_id = s.id 
    WHERE s.teacher_id = ?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$tests = $stmt->get_result();

// Handle question submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_id  = $_POST['test_id'];
    $question = trim($_POST['question']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct  = $_POST['correct'];

    if (!empty($test_id) && !empty($question) && !empty($option_a) && !empty($option_b) && !empty($option_c) && !empty($option_d) && !empty($correct)) {
        $stmt = $conn->prepare("
            INSERT INTO questions (test_id, question, option_a, option_b, option_c, option_d, correct_option)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("issssss", $test_id, $question, $option_a, $option_b, $option_c, $option_d, $correct);
        if ($stmt->execute()) {
            $success = "Question added successfully!";
        } else {
            $error = "Error adding question: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Quiz Question</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image:url('https://wallpapercave.com/wp/wp2590353.jpg');background-size:2000px;">

<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Add Quiz Question</h2>

    <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        <label>Select Test:</label><br>
        <select name="test_id" required>
            <option value="">--Select Test--</option>
            <?php while($row = $tests->fetch_assoc()): ?>
                <option value="<?php echo $row['test_id']; ?>">
                    <?php echo htmlspecialchars($row['subject_name'] . " → " . $row['test_name']); ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Question:</label><br>
        <textarea name="question" required></textarea><br><br>

        <label>Options:</label><br>
        <input type="text" name="option_a" placeholder="Option A" required><br>
        <input type="text" name="option_b" placeholder="Option B" required><br>
        <input type="text" name="option_c" placeholder="Option C" required><br>
        <input type="text" name="option_d" placeholder="Option D" required><br><br>

        <label>Correct Option:</label><br>
        <select name="correct" required>
            <option value="">--Select--</option>
            <option value="A">Option A</option>
            <option value="B">Option B</option>
            <option value="C">Option C</option>
            <option value="D">Option D</option>
        </select><br><br>

        <button type="submit">Add Question</button>
    </form>
</div>
</body>
</html>
