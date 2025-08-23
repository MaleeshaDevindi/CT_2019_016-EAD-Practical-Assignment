<?php
session_start();
require_once "config.php"; 

// Handle login form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);


    if (!empty($username) && !empty($password)) {
        // Escape input to prevent SQL injection
        $username = mysqli_real_escape_string($conn, $username);

        // Query user
        $sql = "SELECT id, username, password_hash, role 
                FROM users 
                WHERE username = '$username' 
                LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password_hash'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect by role
                if ($user['role'] === 'admin') {
                    header("Location: ../admin/index.php");
                } elseif ($user['role'] === 'staff') {
                    header("Location: ../staff/index.php");
                } elseif ($user['role'] === 'student') {
                    header("Location: ../student/index.php");
                } else {
                    $error = "Invalid role. Contact admin.";
                }
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

die($error);

?>
