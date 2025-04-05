<?php
include "../../Connection.php"; // Assumes $conn is defined here

if (isset($_GET['UserId'])&&isset($_GET['ExamId'])&&isset($_GET['ExamName'])) {
    $UserId = intval($_GET['UserId']);
    $ExamId = intval($_GET['ExamId']);
    $ExamName = ($_GET['ExamName']);
    
    // Fetch User Details
    $sql = "SELECT * FROM users WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $UserId);
    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing statement: " . mysqli_error($conn));
    }

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result); // Fetch single row

    mysqli_stmt_close($stmt);

    // Fetch Questions for the Exam
    $sql02 = "SELECT Id, Text FROM questions WHERE ExamId = ?";
    $stmt02 = mysqli_prepare($conn, $sql02);

    if ($stmt02 === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt02, "i", $ExamId);
    if (!mysqli_stmt_execute($stmt02)) {
        die("Error executing statement: " . mysqli_error($conn));
    }

    $result02 = mysqli_stmt_get_result($stmt02);
    $questions = mysqli_fetch_all($result02, MYSQLI_ASSOC); // Fetch all as associative array
    mysqli_stmt_close($stmt02);

    // Fetch Answers for Each Question
    $answers = [];
    foreach ($questions as $question) {
        $QuestionId = $question['Id'];

        $sql03 = "SELECT Text FROM answers WHERE QuestionId = ?";
        $stmt03 = mysqli_prepare($conn, $sql03);

        if ($stmt03 === false) {
            die("Error preparing statement: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt03, "i", $QuestionId);
        if (!mysqli_stmt_execute($stmt03)) {
            die("Error executing statement: " . mysqli_error($conn));
        }

        $result03 = mysqli_stmt_get_result($stmt03);
        $answers[$QuestionId] = mysqli_fetch_all($result03, MYSQLI_ASSOC); // Group answers by question
        mysqli_stmt_close($stmt03);
    }
} else {
    die("UserId and ExamId are required.");
}

if (isset($_POST["QuestionSubmit"])) {
    // Retrieve and sanitize input
    $QuestionText = $_POST["Text"];
    $Answer01 = $_POST["answer1"];
    $Answer02 = $_POST["answer2"];
    $Answer03 = $_POST["answer3"]; // Optional
    $Answer04 = $_POST["answer4"]; // Optional
    $Degree = $_POST["Degree"];
    $UserId02 = intval($_POST['UserId']);
    $ExamId02 = intval($_POST['ExamId']);
    $ExamName02 = ($_POST['ExamName']);
    $CorrectAnswer = ""; // Initialize the correct answer text
    switch ($_POST["CorrectAnswer"]) {
        case "1":
            $CorrectAnswer = $Answer01;
            break;
        case "2":
            $CorrectAnswer = $Answer02;
            break;
        case "3":
            $CorrectAnswer = $Answer03;
            break;
        case "4":
            $CorrectAnswer = $Answer04;
            break;
    }

    // Insert question into questions table
    $sql04 = "INSERT INTO questions (ExamId, Text, Points, RightAnswer) VALUES (?, ?, ?, ?)";
    $stmt04 = mysqli_prepare($conn, $sql04);
    if ($stmt04 === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt04, "isss", $ExamId, $QuestionText, $Degree, $CorrectAnswer);
    if (!mysqli_stmt_execute($stmt04)) {
        die("Error executing statement: " . mysqli_error($conn));
    }

    // Get the last inserted question ID
    $QuestionId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt04);

    // Insert answers into answers table
    $answers = [
        $Answer01,
        $Answer02,
        $Answer03,
        $Answer04
    ];

    foreach ($answers as $index => $answer) {
        if (!empty($answer)) { // Check if the answer is not empty
            $sql05 = "INSERT INTO answers (QuestionId, Text) VALUES (?, ?)";
            $stmt05 = mysqli_prepare($conn, $sql05);
            if ($stmt05 === false) {
                die("Error preparing statement: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt05, "is", $QuestionId, $answer);
            if (!mysqli_stmt_execute($stmt05)) {
                die("Error executing statement: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt05);
        }
    }

    if (!isset($UserId) || !isset($ExamId) || !isset($ExamName)) {
      die("Error: Missing UserId, ExamId, or ExamName before form render.");
      print_r($_POST);
    }
    else{
        // Redirect to the preview page with UserId, ExamId, and ExamName
        header("Location: ../PreviewExam/index.php?UserId=$UserId02&ExamId=$ExamId02&ExamName=$ExamName02");
        exit(); // Ensure no further code is executed after the redirect
        }
}
      //delete button
      if (isset($_GET['delete'])) {
        $deleteId = intval($_GET['delete']);
    
        $sqlDeleteAnswers = "DELETE FROM answers WHERE QuestionId = ?";
        $stmtDeleteAnswers = mysqli_prepare($conn, $sqlDeleteAnswers);
        mysqli_stmt_bind_param($stmtDeleteAnswers, "i", $deleteId);
        mysqli_stmt_execute($stmtDeleteAnswers);
        mysqli_stmt_close($stmtDeleteAnswers);
    
        $sqlDeleteQuestion = "DELETE FROM questions WHERE Id = ?";
        $stmtDeleteQuestion = mysqli_prepare($conn, $sqlDeleteQuestion);
        mysqli_stmt_bind_param($stmtDeleteQuestion, "i", $deleteId);
        mysqli_stmt_execute($stmtDeleteQuestion);
        mysqli_stmt_close($stmtDeleteQuestion);
    
        header("Location: index.php?UserId=$UserId&ExamId=$ExamId&ExamName=$ExamName");
        exit();
    }
      
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <title>Preview Exam</title>
  </head>
  <body>
    <nav class="nav-header">
      <div class="logo">
        <p class="nav-logo">online quiz</p>
        <i class="menu fa-solid fa-bars"></i>
      </div>
      <a href="../../index.php" class="logout">logout</a>
    </nav>
    <div class="content">
      <div class="bar-side">
        <ul>
          <li>
            <div class="imge-info">
              <img draggable="false" src="../../SignUp/image/admin.png" />
            </div>
            <h2><?php echo $row['Name'] ?></h2>
          </li>
          <li>
            <br>
            <h2><?php echo $row['Role'] ?></h2>
          </li>
          <li>
            <a href="../../Home/index.php?UserId=<?php echo $row['Id'] ?>">
              <i class="fa-solid fa-gauge-high"></i>
              <span>dashboard</span>
            </a>
          </li>
          <li>
            <a
              href="../../ExamActionPage/index.php?UserId=<?php echo $row['Id'] ?>"
            >
              <i class="fa-brands fa-steam"></i>
              <span>Exams</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="container">
        <div class="header">
          <!-- php -->
          <h2>Exam Id: <?php echo $ExamId?></h2>
          <h2>Exam Name: <?php echo $ExamName?></h2>
        </div>
        <div class="bodyAllQuation">
         
    <?php foreach ($questions as $question): ?>
      <div class="quationBody">
        <p class="title"><?php echo htmlspecialchars($question['Text']); ?></p>
        <?php if (isset($answers[$question['Id']])): ?>
            <?php foreach ($answers[$question['Id']] as $answer): ?>
                <div class="answer">
                    <span><?php echo htmlspecialchars($answer['Text']); ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <div class="btns">
          <a href="index.php?UserId=<?php echo $UserId ?>&ExamId=<?php echo $ExamId ?>&ExamName=<?php echo $ExamName ?>&delete=<?php echo $question['Id']; ?>" 
             onclick="return confirm('Are you sure you want to delete this question?');">
              <button class="delete">Delete</button>
           </a>
           <a href="EditPage/index.php?UserId=<?php echo $UserId ?>&ExamId=<?php echo $ExamId ?>&ExamName=<?php echo $ExamName ?>&edit=<?php echo $question['Id']; ?>">
       <button class="edit">Edit</button>
    </a>
          </div>
        </div>
    <?php endforeach; ?>
        </div>
      </div>
      <div class="btns-addQuationAndsubmit">
        <a href="#" class="addQuation">Add Question</a>
        <a href="../../Home/index.php?UserId=<?php echo $UserId ?>" class="submitquestion button">Submit Exam</a>
      </div>
      <div class="overlay">
        <form action="index.php?UserId=<?php echo $UserId?>&ExamId=<?php echo $ExamId?>&ExamName=<?php echo $ExamName?>" method="post">
          <i class="close fa-solid fa-xmark"></i>
          <h2>Add Question</h2>
          <div class="quation-title">
            <label for="title">Text</label>
            <input
              type="text"
              name="Text"
              id="title"
              required
              placeholder="Text"
            />
          </div>
          <div class="quation-answer">
            <label for="title">answer</label>
            <input
              type="text"
              name="answer1"
              id="answer1"
              required
              placeholder="Answer1"
            />
            <input
              type="text"
              name="answer2"
              id="answer2"
              required
              placeholder="Answer2"
            />
            <input
              type="text"
              name="answer3"
              id="answer3"
              placeholder="Answer3"
            />
            <input
              type="text"
              name="answer4"
              id="answer4"
              placeholder="Answer4"
            />
          </div>
          <div class="corect-Answer">
            <label for="title">Correct Answer</label>
            <select name="CorrectAnswer" class="CorAnswer" required>
                <option value="1">Answer 1</option>
                <option value="2">Answer 2</option>
                <option value="3">Answer 3</option>
                <option value="4">Answer 4</option>
            </select>
          </div>
          <div class="degreOfQution">
            <label for="title">Points</label>
            <input
              min="1"
              type="number"
              name="Degree"
              id="degre"
              required
              placeholder="Points"
            />
          </div>
          <div class="btns">
            <button type="submit" name="QuestionSubmit">Submit Question</button>
            <input type="hidden" name="UserId" value="<?php echo $UserId; ?>" />
            <input type="hidden" name="ExamId" value="<?php echo $ExamId; ?>" />
            <input type="hidden" name="ExamName" value="<?php echo $ExamName; ?>" />
          </div>
        </form>
      </div>
    </div>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>