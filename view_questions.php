<?php
session_start();
require 'db.php';

// Only teacher can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM questions WHERE id = $id");
    header("Location: view-questions.php");
    exit();
}

// Fetch subjects
$subjects = $conn->query("SELECT * FROM subjects");

// If a subject is selected
$selected_subject = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;

$questions = null;
if ($selected_subject > 0) {
    $questions = $conn->query("SELECT * FROM questions WHERE subject_id = $selected_subject");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Questions - SmartClass</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2>View Quiz Questions</h2>

        <form method="GET" style="margin-bottom:20px;">
            <label>Select Subject</label>
            <select name="subject_id" onchange="this.form.submit()">
                <option value="">-- Select Subject --</option>
                <?php while ($row = $subjects->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id']; ?>" <?php if ($selected_subject == $row['id']) echo "selected"; ?>>
                        <?php echo htmlspecialchars($row['name']); ?>
                    </option>
                <?php } ?>
            </select>
        </form>

        <?php if ($questions && $questions->num_rows > 0) { ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Options</th>
                    <th>Answer</th>
                    <th>Action</th>
                </tr>
                <?php while ($q = $questions->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $q['id']; ?></td>
                        <td><?php echo htmlspecialchars($q['question']); ?></td>
                        <td>
                            1) <?php echo htmlspecialchars($q['option1']); ?><br>
                            2) <?php echo htmlspecialchars($q['option2']); ?><br>
                            3) <?php echo htmlspecialchars($q['option3']); ?><br>
                            4) <?php echo htmlspecialchars($q['option4']); ?>
                        </td>
                        <td><?php echo strtoupper($q['answer']); ?></td>
                        <td>
                            <a href="view_questions.php?delete=<?php echo $q['id']; ?>" onclick="return confirm('Delete this question?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } elseif ($selected_subject) { ?>
            <p>No questions found for this subject.</p>
        <?php } ?>
    </div>
</body>
</html>
