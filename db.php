<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database config
$host = "localhost";
$user = "root";   // change if needed
$pass = "";       // change if needed
$dbname = "smartclass";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

/**
 * Run SELECT queries safely
 */
function db_select($sql, $params = [], $types = "")
{
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("DB Prepare Error: " . $conn->error);
    }

    if (!empty($params)) {
        if ($types === "") {
            $types = str_repeat("s", count($params)); // default all as string
        }
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result(); // For SELECT
}

/**
 * Run INSERT/UPDATE/DELETE safely
 */
function db_execute($sql, $params = [], $types = "")
{
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("DB Prepare Error: " . $conn->error);
    }

    if (!empty($params)) {
        if ($types === "") {
            $types = str_repeat("s", count($params));
        }
        $stmt->bind_param($types, ...$params);
    }

    return $stmt->execute();
}
