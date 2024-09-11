<?php
session_start();
$conn = new mysqli("localhost", "root", "", "entrance_exam_db");

// Admin Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = "SELECT * FROM admin WHERE username='$username'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin_dashboard.php");
        } else {
            echo "<div class='error-message'>Invalid credentials!</div>";
        }
    } else {
        echo "<div class='error-message'>Admin not found!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Entrance Exam</title>
    <style>
        /* You can use the same CSS styles from the student login page */
    </style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>

    <form method="POST">
        <div class="input-field">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-field">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" name="login" class="submit-btn">Login</button>
    </form>

    <div class="form-footer">
        <a href="index.php" style="color: #2980b9; text-decoration: none;">Student? Login here</a>
    </div>
</div>

</body>
</html>
