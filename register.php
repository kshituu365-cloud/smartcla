<?php
require "db.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? '';

    // Basic validation
    if ($username === '' || $email === '' || $password === '' || $role === '') {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (!in_array($role, ['student','teacher'], true)) {
        $error = "Invalid role.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check duplicates
        $dupU = db_select("SELECT id FROM users WHERE username = ? LIMIT 1", [$username]);
        if ($dupU && $dupU->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            $dupE = db_select("SELECT id FROM users WHERE email = ? LIMIT 1", [$email]);
            if ($dupE && $dupE->num_rows > 0) {
                $error = "Email already in use.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $ok = db_execute(
                    "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)",
                    [$username, $email, $hashed, $role]
                );
                if ($ok) {
                    $success = "Registration successful! <a href='login.php'>Login</a>";
                } else {
                    // Optional one-liner to see MySQL error while debugging:
                    // echo "<pre>MySQL error: " . htmlspecialchars($GLOBALS['conn']->error) . "</pre>";
                    $error = "Something went wrong. Try again.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register - SmartClass</title>
    <link rel="stylesheet" href="style.css"><!-- ensure path matches your CSS -->
</head>
<body style="background-image:url('https://static.vecteezy.com/system/resources/thumbnails/024/740/904/small_2x/welcome-back-to-school-background-design-with-copy-space-for-adding-text-concept-of-education-school-chalkboard-with-different-stuff-vector.jpg');background-size:cover;">

<div class="container">
    <h2>Register</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required minlength="6">

        <label>Role</label>
        <select name="role" required>
            <option value="">-- Select --</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select>

        <button type="submit">Register</button>
    </form>

    <p>Already registered? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
