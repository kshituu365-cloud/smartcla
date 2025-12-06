<?php

require "db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check user in DB
    $result = db_select("SELECT id, username, password, role FROM users WHERE username = ?", [$username]);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Save session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'teacher') {
                header("Location: teacher-dashboard.php");
                exit;
            } else {
                header("Location: student-dashboard.php");
                exit;
            }
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - SmartClass</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<center><br><br><br><br>
<br><br><br><br>
<br><br><br>
<br>
<br>
<br>
    <h2 style="color:white; font-size:50px">Login</h2>
<body style="background-image:url('https://static.vecteezy.com/system/resources/thumbnails/024/740/904/small_2x/welcome-back-to-school-background-design-with-copy-space-for-adding-text-concept-of-education-school-chalkboard-with-different-stuff-vector.jpg');background-size:cover;">
    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post">
        <label style="color:white; font-size:30px;">Username:</label><br>
        <input style="width:500px; height:30px;" type="text" name="username" required><br><br>

        <label style="color:white;  font-size:30px;">Password:</label><br>
        <input style="width:500px; height:30px;" type="password" name="password" required><br><br>

        <button style="width:100px; height:30px;" type="submit">Login</button>
    </form>
    <p style="color:white; font-size:30px">Don't have an account? <a href="register.php" style="color:skyblue";>Register here</a></p>
</body>
</html>
