<?php 
include "../../../Connection.php"; // Assumes $conn is defined here

//edit button
      $editData = [];
        if (isset($_GET['edit'])) {
          $UserId = intval($_GET["UserId"]);  
          $ExamId = intval($_GET["ExamId"]);  
          $ExamName = $_GET["ExamName"];  
          $editId = intval($_GET['edit']);

            $sqlEditQuestion = "SELECT * FROM questions WHERE Id = ?";
            $stmtEditQuestion = mysqli_prepare($conn, $sqlEditQuestion);
            mysqli_stmt_bind_param($stmtEditQuestion, "i", $editId);
            mysqli_stmt_execute($stmtEditQuestion);
            $resultEditQuestion = mysqli_stmt_get_result($stmtEditQuestion);
            $editData = mysqli_fetch_assoc($resultEditQuestion);
            mysqli_stmt_close($stmtEditQuestion);

            // Fetch the answers for the question
            $sqlFetchAnswers = "SELECT Text FROM answers WHERE QuestionId = ?";
            $stmtFetchAnswers = mysqli_prepare($conn, $sqlFetchAnswers);
            mysqli_stmt_bind_param($stmtFetchAnswers, "i", $editId);
            mysqli_stmt_execute($stmtFetchAnswers);
            $resultFetchAnswers = mysqli_stmt_get_result($stmtFetchAnswers);
            
            while ($row = mysqli_fetch_assoc($resultFetchAnswers)) {
                $answers[] = $row['Text']; // Store the answer texts in an array
            }
            mysqli_stmt_close($stmtFetchAnswers);
        }
      //updateQuestion button
// Update Question and Answers
if (isset($_POST['UpdateQuestion'])) {
    $QuestionId = intval($_POST['QuestionId']);
    $QuestionText = $_POST['Text'];
    $Degree = $_POST['Degree'];
    $Answer01 = $_POST["answer1"];
    $Answer02 = $_POST["answer2"];
    $Answer03 = $_POST["answer3"]; // Optional
    $Answer04 = $_POST["answer4"]; // Optional
    $CorrectAnswer = $_POST['CorrectAnswer'];
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
    
    // Update the question
    $sqlUpdateQuestion = "UPDATE questions SET Text = ?, Points = ?, RightAnswer = ? WHERE Id = ?";
    $stmtUpdateQuestion = mysqli_prepare($conn, $sqlUpdateQuestion);
    mysqli_stmt_bind_param($stmtUpdateQuestion, "sssi", $QuestionText, $Degree, $CorrectAnswer, $QuestionId);
    mysqli_stmt_execute($stmtUpdateQuestion);
    mysqli_stmt_close($stmtUpdateQuestion);

    // Update the answers
    // Assuming you have the answer IDs stored in an array (you need to modify your form to include these IDs)
    $answers = [
        $_POST['answer1'],
        $_POST['answer2'],
        $_POST['answer3'],
        $_POST['answer4']
    ];

    // First, delete existing answers for the question
    $sqlDeleteAnswers = "DELETE FROM answers WHERE QuestionId = ?";
    $stmtDeleteAnswers = mysqli_prepare($conn, $sqlDeleteAnswers);
    mysqli_stmt_bind_param($stmtDeleteAnswers, "i", $QuestionId);
    mysqli_stmt_execute($stmtDeleteAnswers);
    mysqli_stmt_close($stmtDeleteAnswers);

    // Then, insert the updated answers
    foreach ($answers as $answer) {
        if (!empty($answer)) { // Only insert non-empty answers
            $sqlInsertAnswer = "INSERT INTO answers (QuestionId, Text) VALUES (?, ?)";
            $stmtInsertAnswer = mysqli_prepare($conn, $sqlInsertAnswer);
            mysqli_stmt_bind_param($stmtInsertAnswer, "is", $QuestionId, $answer);
            mysqli_stmt_execute($stmtInsertAnswer);
            mysqli_stmt_close($stmtInsertAnswer);
        }
    }

    // Redirect after updating
    header("Location: ../index.php?UserId=$UserId&ExamId=$ExamId&ExamName=$ExamName");
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
    <title>Edit Page</title>
  </head>
  <body>
    <div class="overlay overlay-edit">
      <form action="#" method="post">
    <input type="hidden" name="QuestionId" value="<?php echo $editData['Id']; ?>">
    <a href="../index.php?UserId=<?php echo $UserId ?>&ExamId=<?php echo $ExamId ?>&ExamName=<?php echo $ExamName ?>"> <i class="close fa-solid fa-xmark"></i></a>
    <h2>Update Question</h2>
    <div class="quation-title">
        <label for="title">Text</label>
        <input
            type="text"
            name="Text"
            id="title"
            required
            placeholder="Title"
            value="<?php echo htmlspecialchars($editData["Text"]); ?>"
        />
    </div>
    <div class="quation-answer">
        <label for="title">Answer</label>
        <input
            type="text"
            name="answer1"
            id="answer1"
            required
            placeholder="Answer1"
            value="<?php echo isset($answers[0]) ? htmlspecialchars($answers[0]) : ''; ?>"
        />
        <input
            type="text"
            name="answer2"
            id="answer2"
            required
            placeholder="Answer2"
            value="<?php echo isset($answers[1]) ? htmlspecialchars($answers[1]) : ''; ?>"
        />
        <input
            type="text"
            name="answer3"
            id="answer3"
            placeholder="Answer3"
            value="<?php echo isset($answers[2]) ? htmlspecialchars($answers[2]) : ''; ?>"
        />
        <input
            type="text"
            name="answer4"
            id="answer4"
            placeholder="Answer4"
            value="<?php echo isset($answers[3]) ? htmlspecialchars($answers[3]) : ''; ?>"
        />
    </div>
    <div class="corect-Answer">
        <label for="title">Correct Answer</label>
        <select name="CorrectAnswer" class="CorAnswer">
            <option value="1" <?php echo (isset($answers[0]) && $answers[0] == $editData['RightAnswer']) ? 'selected' : ''; ?>>Option1</option>
            <option value="2" <?php echo (isset($answers[1]) && $answers[1] == $editData['RightAnswer']) ? 'selected' : ''; ?>>Option2</option>
            <option value="3" <?php echo (isset($answers[2]) && $answers[2] == $editData['RightAnswer']) ? 'selected' : ''; ?>>Option3</option>
            <option value="4" <?php echo (isset($answers[3]) && $answers[3] == $editData['RightAnswer']) ? 'selected' : ''; ?>>Option4</option>
        </select>
    </div>
    <div class="degreOfQution">
        <label for="title">Degree</label>
        <input
            min="1"
            type="number"
            name="Degree"
            id="degre"
            required
            placeholder="Degree"
            value="<?php echo (int)$editData["Points"]; ?>"
        />
    </div>
    <div class="btns">
        <button type="submit" name="UpdateQuestion">Update</button>
    </div>
</form>
    </div>
  </body>
</html>
