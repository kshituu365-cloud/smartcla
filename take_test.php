<?php
session_start();
require "db.php"; 

// Check if user logged in
if (!isset($_SESSION['user_id'])) {
    echo "❌ You must be logged in to take the test";
    exit();
}

// Get test_id dynamically from URL
if (!isset($_GET['test_id'])) {
    echo "❌ No test selected.";
    exit();
}
$test_id = intval($_GET['test_id']);

// Fetch questions
$sql = "SELECT * FROM questions WHERE test_id = $test_id";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Test</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-image: url('https://wallpapercave.com/wp/wp2590353.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
            text-align: center;
        }
        h2 {
            margin-top: 20px;
            font-size: 28px;
            color: #ffeb3b;
            text-shadow: 2px 2px 4px #000;
        }
        .question-box {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            margin: 15px auto;
            width: 70%;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            text-align: left;
        }
        .question-box p {
            font-size: 18px;
            font-weight: bold;
            color: #ff9800;
        }
        .question-box label {
            display: block;
            background: rgba(255,255,255,0.1);
            margin: 8px 0;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        .question-box input[type="radio"] {
            margin-right: 10px;
        }
        .question-box label:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.02);
        }
        button {
            margin: 20px;
            padding: 12px 25px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            background: #ff5722;
            color: #fff;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #e64a19;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <h2>📝 Take Test</h2>
    <form method="post" action="submit_test.php">
        <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">

        <?php 
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='question-box'>";
                echo "<p>❓ " . htmlspecialchars($row['question']) . "</p>";
                echo "<label><input type='radio' name='answers[".$row['id']."]' value='A'> " . htmlspecialchars($row['option_a']) . "</label>";
                echo "<label><input type='radio' name='answers[".$row['id']."]' value='B'> " . htmlspecialchars($row['option_b']) . "</label>";
                echo "<label><input type='radio' name='answers[".$row['id']."]' value='C'> " . htmlspecialchars($row['option_c']) . "</label>";
                echo "<label><input type='radio' name='answers[".$row['id']."]' value='D'> " . htmlspecialchars($row['option_d']) . "</label>";
                echo "</div>";
            }
        } else {
            echo "<p>No questions found for this test.</p>";
        }
        ?>
        <button type="submit">✅ Submit Test</button>
    </form>
</body>
</html>
