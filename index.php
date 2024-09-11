<?php
session_start();
$conn = new mysqli("localhost", "root", "", "entrance_exam_db");

// Handling registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // 'student' or 'admin'

    if ($role == 'student') {
        // Register student
        $conn->query("INSERT INTO students (email, password) VALUES ('$email', '$password')");
        echo "<div class='success-message'>Student registration successful!</div>";
    } elseif ($role == 'admin') {
        // Register admin
        $conn->query("INSERT INTO admin (username, password) VALUES ('$email', '$password')");
        echo "<div class='success-message'>Admin registration successful!</div>";
    }
}

// Handling login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == 'student') {
        // Student login
        $result = $conn->query("SELECT * FROM students WHERE email='$email'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['student_logged_in'] = true;
                $_SESSION['student_id'] = $row['id'];
                header("Location: exam.php");
            } else {
                echo "<div class='error-message'>Invalid credentials for student!</div>";
            }
        }
    } elseif ($role == 'admin') {
        // Admin login
        $result = $conn->query("SELECT * FROM admin WHERE username='$email'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_logged_in'] = true;
                header("Location: admin_dashboard.php");
            } else {
                echo "<div class='error-message'>Invalid credentials for admin!</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Entrance Exam System</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(120deg, #2980b9, #6dd5fa, #ffffff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background-color: white;
            padding: 40px;
            max-width: 450px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .input-field {
            width: 100%;
            margin-bottom: 20px;
        }
        .input-field input, .input-field select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            outline: none;
        }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #1c6a98;
        }
        .error-message, .success-message {
            text-align: center;
            color: red;
            margin-bottom: 20px;
        }
        .success-message {
            color: green;
        }
        .toggle-form {
            text-align: center;
            margin-top: 20px;
        }
        .toggle-form a {
            color: #2980b9;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2 id="form-title">Login</h2>

    <!-- Login Form -->
    <form id="login-form" method="POST">
        <div class="input-field">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-field">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-field">
            <select name="role" required>
                <option value="student">Student</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" name="login" class="submit-btn">Login</button>
    </form>

    <!-- Registration Form -->
    <form id="register-form" method="POST" style="display: none;">
        <div class="input-field">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-field">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-field">
            <select name="role" required>
                <option value="student">Student</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" name="register" class="submit-btn">Register</button>
    </form>

    <div class="toggle-form">
        <span id="toggle-text">Don't have an account?</span>
        <a id="toggle-link" onclick="toggleForms()">Register here</a>
    </div>
</div>

<script>
    function toggleForms() {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const formTitle = document.getElementById('form-title');
        const toggleText = document.getElementById('toggle-text');
        const toggleLink = document.getElementById('toggle-link');
        
        if (registerForm.style.display === 'none') {
            // Show registration form
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            formTitle.innerHTML = 'Register';
            toggleText.innerHTML = 'Already have an account?';
            toggleLink.innerHTML = 'Login here';
        } else {
            // Show login form
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
            formTitle.innerHTML = 'Login';
            toggleText.innerHTML = "Don't have an account?";
            toggleLink.innerHTML = 'Register here';
        }
    }
</script>

</body>
</html>
