<?php

include "../Connection.php"; // Assumes $conn is defined here

if (isset($_GET['UserId'])) {
    $Id = $_GET['UserId'];
    
    // Prepare SQL query with placeholders
    $sql02 = "SELECT * FROM `users` WHERE `Id` = $Id";

    // Initialize a statement
    $stmt02 = mysqli_prepare($conn, $sql02);

    if ($stmt02 === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Execute the statement
    if (!mysqli_stmt_execute($stmt02)) {
        die("Error executing statement: " . mysqli_error($conn));
    }

    // Get the result
    $result02 = mysqli_stmt_get_result($stmt02);

    if (mysqli_num_rows($result02) > 0) {
        // Fetch the user's data
        $row02 = mysqli_fetch_assoc($result02);
        $last_id = $row02['Id'];
    }

}

$questions = []; // To store questions and answers
if (isset($_GET['ExamId'])) {
    $ExamId = intval($_GET['ExamId']); // Sanitize input
    $UserId = intval($_GET['UserId']); // Sanitize input

    // Fetch questions and answers in a single query using JOIN
    $sql = "
        SELECT q.Id AS QuestionId, q.Text AS QuestionText, a.Text AS AnswerText
        FROM Questions q
        LEFT JOIN answers a ON q.Id = a.QuestionId
        WHERE q.ExamId = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Bind and execute the statement
    mysqli_stmt_bind_param($stmt, "i", $ExamId);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        // Organize questions and their answers
        while ($row = mysqli_fetch_assoc($result)) {
            $questions[$row['QuestionId']]['text'] = $row['QuestionText'];
            $questions[$row['QuestionId']]['answers'][] = $row['AnswerText'];
        }
    } else {
        die("Error executing statement: " . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <title>Take Exam</title>
</head>
<body>
    <nav class="nav-header">
        <div class="logo">
            <p class="nav-logo">online quiz</p>
            <i class="menu fa-solid fa-bars"></i>
        </div>
        <a href="../index.php" class="logout">logout</a>
    </nav>
    <div class="content">
        <div class="bar-side">
        <ul>
          <li>
            <div class="imge-info">
              <img draggable="false" src="../SignUp/image/admin.png" />
            </div>
            <h2><?php echo $row02['Name'] ?></h2>
          </li>
          <li>
            <br>
            <h2><?php echo $row02['Role'] ?></h2>
          </li>
          <li>
            <a href="../Home/index.php?UserId=<?php echo $row02['Id']?>">
              <i class="fa-solid fa-gauge-high"></i>
              <span>dashboard</span>
            </a>
          </li>
          <li>
            <a
              href="../ExamActionPage/index.php?UserId=<?php echo $row02['Id'] ?>"
            >
              <i class="fa-brands fa-steam"></i>
              <span>Exams</span>
            </a>
          </li>
        </ul>
      </div>
        <div class="StartExam">
            <h2>Start Exam</h2>
            <form action="CalcGrade.php" method="post">
    <input type="hidden" name="ExamId" value="<?php echo $ExamId; ?>">
    <input type="hidden" name="UserId" value="<?php echo $UserId; ?>">

    <?php foreach ($questions as $questionId => $questionData): ?>
        <div class="question">
            <br>
            <p><?php echo htmlspecialchars($questionData['text']); ?></p>
            <br>
            <?php foreach ($questionData['answers'] as $answer): ?>
                <input type="radio" name="answers[<?php echo $questionId; ?>]" value="<?php echo htmlspecialchars($answer); ?>" required>
                <span><?php echo htmlspecialchars($answer); ?></span>
                <br>
                <br>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <button style="width : 100%; border : none; outline : none; padding : 10px; background-color : rgba(0,0,255,0.5); color : white; border-radius : 10px; font-size : 20px" type="submit" name="Submit">Submit</button>
</form>
        </div>
    </div>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/main.js"></script>
</body>
</html>

