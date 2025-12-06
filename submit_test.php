<?php
session_start();
require "db.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    echo "❌ You must be logged in to submit the test";
    exit();
}

$user_id = $_SESSION['user_id'];
$test_id = $_POST['test_id'] ?? 0;
$answers = $_POST['answers'] ?? [];

// Fetch correct answers
$sql = "SELECT id, correct_option FROM questions WHERE test_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $test_id);
$stmt->execute();
$result = $stmt->get_result();

$score = 0;
$total = $result->num_rows;

while ($row = $result->fetch_assoc()) {
    $qid = $row['id'];
    $correct = $row['correct_option'];

    if (isset($answers[$qid]) && $answers[$qid] === $correct) {
        $score++;
    }
}

// Save result in `results` table
$sql = "INSERT INTO results (student_id, test_id, score) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error on Insert: " . $conn->error);
}

$stmt->bind_param("iii", $user_id, $test_id, $score);
$stmt->execute();


echo "<h2 style='text-align:center;color:green'><br><br><br><br>✅ Test Submitted Successfully!</h2>";
echo "<p style='text-align:center;font-size:20px'>Your Score: $score / $total</p>";
echo "<div style='text-align:center;margin-top:20px;'>
        <a href='student-dashboard.php' style='padding:10px 20px;background:#ff9800;color:#fff;text-decoration:none;border-radius:8px;'>🏠 Go Home</a>
      </div>";
?>
