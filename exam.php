<?php
session_start();
$conn = new mysqli("localhost", "root", "", "entrance_exam_db");

if (!isset($_SESSION['student_logged_in'])) {
    header("Location: index.php");
}

$student_id = $_SESSION['student_id'];
$questions = $conn->query("SELECT * FROM questions");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_exam'])) {
    $score = 0;
    foreach ($_POST['answers'] as $question_id => $answer) {
        $query = "SELECT correct_option FROM questions WHERE id=$question_id";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        if ($row['correct_option'] == $answer) {
            $score++;
        }
    }

    $conn->query("INSERT INTO results (student_id, score) VALUES ($student_id, $score)");
    echo "<div class='form-result'><h3>Exam completed!</h3><p>Your score is: $score</p></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Page</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .form-container {
            background-color: white;
            max-width: 700px;
            margin: 50px auto;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        .question {
            margin-bottom: 20px;
        }
        .question-text {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }
        .options label {
            display: block;
            margin-bottom: 5px;
            padding: 5px;
            background-color: #f1f1f1;
            border-radius: 5px;
            cursor: pointer;
        }
        .options input[type="radio"] {
            margin-right: 10px;
        }
        .submit-button {
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .submit-button:hover {
            background-color: #1a6f8f;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Answer the Questions</h2>

    <form method="POST">
        <?php while ($row = $questions->fetch_assoc()) { ?>
            <div class="question">
                <p class="question-text"><?php echo $row['question_text']; ?></p>
                <div class="options">
                    <label><input type="radio" name="answers[<?php echo $row['id']; ?>]" value="A" required> <?php echo $row['option_a']; ?></label>
                    <label><input type="radio" name="answers[<?php echo $row['id']; ?>]" value="B"> <?php echo $row['option_b']; ?></label>
                    <label><input type="radio" name="answers[<?php echo $row['id']; ?>]" value="C"> <?php echo $row['option_c']; ?></label>
                    <label><input type="radio" name="answers[<?php echo $row['id']; ?>]" value="D"> <?php echo $row['option_d']; ?></label>
                </div>
            </div>
        <?php } ?>
        <button type="submit" name="submit_exam" class="submit-button">Submit Exam</button>
    </form>
</div>

</body>
</html>
