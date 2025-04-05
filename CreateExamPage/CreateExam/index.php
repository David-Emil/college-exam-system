<?php
include "../../Connection.php"; // Assumes $conn is defined here

global $UserId;
  
if (isset($_GET['UserId'])) {
  $UserId = intval($_GET['UserId']); // Sanitize input
    
    // Fetch user to validate existence
    $sql = "SELECT * FROM `users` WHERE `Id` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $UserId);
    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing statement: " . mysqli_error($conn));
    }
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) === 0) {
        die("User not found.");
    }
    else{
      $row = mysqli_fetch_assoc($result);
    }
    mysqli_stmt_close($stmt);
}

if (isset($_POST["Submit"])) {
    // Retrieve and sanitize input
    $ExamName = $_POST["ExamName"];
    $ExamType = $_POST["ExamType"];
    $ExamTime = intval($_POST["ExamTime"]);
    
    // Retrieve UserId from POST data
    $UserId = intval($_POST["UserId"]); // Sanitize input

    if ($UserId === null) {
        die("Error: UserId is not set.");
    }

    // Insert exam details into `exams` table
    $sql03 = "INSERT INTO `exams` (`Name`, `Type`, `CreatedBy`, `Duration`) VALUES (?, ?, ?, ?)";
    $stmt03 = mysqli_prepare($conn, $sql03);
    if ($stmt03 === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt03, "ssii", $ExamName, $ExamType, $UserId, $ExamTime);
    if (!mysqli_stmt_execute($stmt03)) {
        die("Error executing statement: " . mysqli_error($conn));
    }
    $result03 = mysqli_stmt_affected_rows($stmt03);
    mysqli_stmt_close($stmt03);

    // Retrieve the newly created ExamId
    $sql02 = "SELECT `Id` FROM `exams` WHERE `Name` = ?";
    $stmt02 = mysqli_prepare($conn, $sql02);
    if ($stmt02 === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt02, "s", $ExamName);
    if (!mysqli_stmt_execute($stmt02)) {
        die("Error executing statement: " . mysqli_error($conn));
    }
    $result02 = mysqli_stmt_get_result($stmt02);
    if ($row02 = mysqli_fetch_assoc($result02)) {
        $ExamId = $row02['Id'];
    }
    mysqli_stmt_close($stmt02);

    if ($result03 > 0) {
        // Redirect to the preview page
        header("Location: ../PreviewExam/index.php?UserId=$UserId&ExamId=$ExamId&ExamName=$ExamName");
        exit();
    }
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
    <title>Create Exam</title>
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
      <div class="man-content">
        <form action="index.php?UserId=<?php echo $UserId; ?>" method="post">
          <h2>create a new exam</h2>
          <div class="exam-name-div">
            <p>exam name</p>
            <input
              required
              type="text"
              placeholder="enter exam name"
              class="exam-name"
              id="examName"
              name="ExamName"
            />
          </div>
          <div class="exam-time">
            <p>exam time</p>
            <div class="inputForTime">
              <input
                required
                type="number"
                placeholder="Minutes"
                class="exam-time-M"
                id="examTimeM"
                name="ExamTime"
                min="1"
              />
            </div>
          </div>
          <div class="exam-type">
            <p>exam type</p>
            <select name="ExamType" id="examType">
              <option value="quiz">Quiz</option>
              <option value="midterm">Midterm</option>
              <option value="final">Final</option>
            </select>
          </div>
          <div class="btn">
            <button type="submit" name="Submit" >create exam</button>
            <input type="hidden" name="UserId" value="<?php echo $UserId; ?>" /> <!-- Corrected name -->
          </div>
        </form>
      </div>
    </div>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
