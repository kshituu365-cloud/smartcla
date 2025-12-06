<?php
session_start();
require 'db.php';

// Ensure only student can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image:url('https://wallpapercave.com/wp/wp2590353.jpg');background-size:2000px;">

    <?php include 'navbar.php'; ?>

    <div style="width:800px; height:1000px;" class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (Student)</h2>
        <br><center>
        <div class="dashboard-options">
            <a href="view_materials.php" class="btn">📂 View Study Materials</a>
                        <br><br>
            <br><br>

            <a href="view_results.php" class="btn">📊 My Results</a>
                        <br><br>

        </div>

        <h3>📝 Available Tests</h3>
        <ul>
            <?php
            // Fetch tests
            $result = $conn->query("SELECT tests.id, tests.name AS test_name, subjects.name AS subject_name 
                                    FROM tests 
                                    JOIN subjects ON tests.subject_id = subjects.id");

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>";
                    echo htmlspecialchars($row['test_name']) . " (". htmlspecialchars($row['subject_name']) . ")";
                    echo " - <a href='take_test.php?test_id=" . $row['id'] . "' class='btn'>Take Test</a>";
                                        echo"<br>";
                                                            echo"<br>";
                    echo"<br>";

                                           echo "</li>";
                }
            } else {
                echo "<li>No tests available yet.</li>";
            }
            ?>
        </ul>
    </div>
</body>
</html>
