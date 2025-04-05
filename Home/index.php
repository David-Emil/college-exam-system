<?php

include "../Connection.php"; // Assumes $conn is defined here
if (isset($_GET['UserId'])) {
    $Id = $_GET['UserId'];
    
    // Prepare SQL query with placeholders
    $sql = "SELECT * FROM `users` WHERE `Id` = $Id";

    // Initialize a statement
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Execute the statement
    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing statement: " . mysqli_error($conn));
    }

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Fetch the user's data
        $row = mysqli_fetch_assoc($result);
    }
}

$sql02 = "SELECT COUNT(Id) FROM `users` WHERE `Role` = \"Student\"";

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
        $StudentsCount = mysqli_fetch_assoc($result02);
    }

    $sql03 = "SELECT COUNT(Id) FROM `users` WHERE `Role` = \"Teacher\"";

    // Initialize a statement
    $stmt03 = mysqli_prepare($conn, $sql03);

    if ($stmt03 === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Execute the statement
    if (!mysqli_stmt_execute($stmt03)) {
        die("Error executing statement: " . mysqli_error($conn));
    }

    // Get the result
    $result03 = mysqli_stmt_get_result($stmt03);

    if (mysqli_num_rows($result03) > 0) {
        // Fetch the user's data
        $TeachersCount = mysqli_fetch_assoc($result03);
    }

    $sql04 = "SELECT COUNT(Id) FROM `exams` WHERE 1";

    // Initialize a statement
    $stmt04 = mysqli_prepare($conn, $sql04);

    if ($stmt04 === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Execute the statement
    if (!mysqli_stmt_execute($stmt04)) {
        die("Error executing statement: " . mysqli_error($conn));
    }

    // Get the result
    $result04 = mysqli_stmt_get_result($stmt04);

    if (mysqli_num_rows($result04) > 0) {
        // Fetch the user's data
        $ExamsCount = mysqli_fetch_assoc($result04);
    }

    // Close statement and connection
    mysqli_stmt_close($stmt02);
    mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <title>Home</title>
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
            <h2><?php echo $row['Name'] ?></h2>
          </li>
          <li>
            <br>
            <h2><?php echo $row['Role'] ?></h2>
          </li>
          <li>
            <a href="index.php?UserId=<?php echo $row['Id']?>">
              <i class="fa-solid fa-gauge-high"></i>
              <span>dashboard</span>
            </a>
          </li>
          <li>
            <a
              href="../ExamActionPage/index.php?UserId=<?php echo $row['Id'] ?>"
            >
              <i class="fa-brands fa-steam"></i>
              <span>Exams</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="main-content">
        <div class="total-student">
          <a href="../StudentPage/index.php?UserId=<?php echo $row['Id'] ?>&Count=<?php echo $StudentsCount["COUNT(Id)"] ?>">
            <p>Total Students</p>
            <div class="info">
              <i class="fa-solid fa-graduation-cap"></i>
              <span><?php echo $StudentsCount["COUNT(Id)"] ?></span>
            </div>
          </a>
        </div>
        <div class="total-teacher">
          <a href="../TeacherPage/index.php?UserId=<?php echo $row['Id'] ?>&Count=<?php echo $TeachersCount["COUNT(Id)"] ?>">
            <p>Total Teachers</p>
            <div class="info">
              <i class="fa-solid fa-chalkboard-user"></i>
              <span><?php echo $TeachersCount["COUNT(Id)"] ?></span>
            </div>
          </a>
        </div>
        <div class="total-exam">
          <a href="../ExamPage/index.php?UserId=<?php echo $row['Id'] ?>&Count=<?php echo $ExamsCount["COUNT(Id)"] ?>">
            <p>Total Exams</p>
            <div class="info">
              <i class="fa-brands fa-steam"></i>
              <span><?php echo $ExamsCount["COUNT(Id)"] ?></span>
            </div>
          </a>
        </div>
      </div>
    </div>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
