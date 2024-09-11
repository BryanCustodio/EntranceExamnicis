<?php
session_start();
$conn = new mysqli("localhost", "root", "", "entrance_exam_db");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// Handle adding new questions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_question'])) {
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];

    $stmt = $conn->prepare("INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option);
    $stmt->execute();
    echo "<div class='success-message'>Question added successfully!</div>";
}

// Handle updating a question
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_question'])) {
    $question_id = $_POST['question_id'];
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];

    $stmt = $conn->prepare("UPDATE questions SET question_text=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=? WHERE id=?");
    $stmt->bind_param("ssssssi", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option, $question_id);
    $stmt->execute();
    echo "<div class='success-message'>Question updated successfully!</div>";
}

// Handle deleting a question
if (isset($_GET['delete_question'])) {
    $question_id = $_GET['delete_question'];
    $conn->query("DELETE FROM questions WHERE id=$question_id");
    echo "<div class='success-message'>Question deleted successfully!</div>";
}

// Handle logging out
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

// Fetching data
$students = $conn->query("SELECT id, email FROM students");
$questions = $conn->query("SELECT * FROM questions");
$results = $conn->query("SELECT students.email, results.score FROM results JOIN students ON results.student_id = students.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .dashboard-container {
            margin: 20px;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2980b9;
            color: white;
            padding: 15px;
            border-radius: 10px;
        }
        .dashboard-header h1 {
            margin: 0;
        }
        .logout-btn {
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        .success-message {
            color: green;
            margin-bottom: 20px;
            text-align: center;
        }
        .error-message {
            color: red;
            margin-bottom: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #2980b9;
            color: white;
        }
        .form-input {
            margin-bottom: 10px;
        }
        .form-input input, .form-input select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .submit-btn {
            background-color: #2980b9;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #1c6a98;
        }
        .action-btn {
            background-color: #e67e22;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .action-btn:hover {
            background-color: #d35400;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <a href="?logout=true" class="logout-btn">Logout</a>
    </div>

    <!-- Manage Questions -->
    <div class="section">
        <h2>Manage Questions</h2>
        <form method="POST">
            <div class="form-input">
                <input type="text" name="question_text" placeholder="Question" required>
            </div>
            <div class="form-input">
                <input type="text" name="option_a" placeholder="Option A" required>
            </div>
            <div class="form-input">
                <input type="text" name="option_b" placeholder="Option B" required>
            </div>
            <div class="form-input">
                <input type="text" name="option_c" placeholder="Option C" required>
            </div>
            <div class="form-input">
                <input type="text" name="option_d" placeholder="Option D" required>
            </div>
            <div class="form-input">
                <select name="correct_option" required>
                    <option value="">Select Correct Option</option>
                    <option value="A">Option A</option>
                    <option value="B">Option B</option>
                    <option value="C">Option C</option>
                    <option value="D">Option D</option>
                </select>
            </div>
            <button type="submit" name="add_question" class="submit-btn">Add Question</button>
        </form>
        <hr>
        <h3>Update or Delete Questions</h3>
        <form method="POST">
            <div class="form-input">
                <input type="number" name="question_id" placeholder="Question ID" required>
            </div>
            <div class="form-input">
                <input type="text" name="question_text" placeholder="New Question Text">
            </div>
            <div class="form-input">
                <input type="text" name="option_a" placeholder="New Option A">
            </div>
            <div class="form-input">
                <input type="text" name="option_b" placeholder="New Option B">
            </div>
            <div class="form-input">
                <input type="text" name="option_c" placeholder="New Option C">
            </div>
            <div class="form-input">
                <input type="text" name="option_d" placeholder="New Option D">
            </div>
            <div class="form-input">
                <select name="correct_option">
                    <option value="">Select New Correct Option</option>
                    <option value="A">Option A</option>
                    <option value="B">Option B</option>
                    <option value="C">Option C</option>
                    <option value="D">Option D</option>
                </select>
            </div>
            <button type="submit" name="update_question" class="submit-btn">Update Question</button>
        </form>
        <hr>
        <h3>Delete a Question</h3>
        <form method="GET">
            <div class="form-input">
                <input type="number" name="delete_question" placeholder="Question ID" required>
            </div>
            <button type="submit" class="submit-btn">Delete Question</button>
        </form>
    </div>

    <!-- Registered Students -->
    <div class="section">
        <h2>Registered Students</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Email</th>
            </tr>
            <?php while ($row = $students->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Exam Results -->
    <div class="section">
        <h2>Exam Results</h2>
        <table>
            <tr>
                <th>Student Email</th>
                <th>Score</th>
            </tr>
            <?php while ($row = $results->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['score']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>
