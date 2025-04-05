<?php

include "../Connection.php"; // Assumes $conn is defined here
if (isset($_GET['UserId'])) {
    $Id = $_GET['UserId'];
    $Count = $_GET['Count'];
    
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
        $last_id = $row['Id'];
    }

}

$sql2 = "SELECT `Id`, `Name` FROM `users` WHERE `Role` = \"Teacher\"";
$stmt2 = mysqli_prepare($conn, $sql2);

    if ($stmt2 === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Execute the statement
    if (!mysqli_stmt_execute($stmt2)) {
        die("Error executing statement: " . mysqli_error($conn));
    }

    // Get the result
    $result2 = mysqli_stmt_get_result($stmt2);

    if (mysqli_num_rows($result2) > 0) {
        // Fetch the user's data
        $rows2 = mysqli_fetch_all($result2);
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <title>Exams</title>
  </head>
  <body>
    <div class="container">
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
            <a href="../Home/index.php?UserId=<?php echo $row['Id'] ?>">
              <i class="fa-solid fa-gauge-high"></i>
              <span>dashboard</span>
            </a>
          </li>
          <li>
            <a
              href="../ExamActionPage/index.php?UserId=<?php echo $row['Id'] ?>">
              <i class="fa-brands fa-steam"></i>
              <span>Exams</span>
            </a>
          </li>
        </ul>
      </div>
        <div class="main-content">
          <p class="header">Total Teachers = <?php echo $Count ?></p>
          <div class="total-student">
            <table>
              <thead>
                <tr>
                  <th>id</th>
                  <th>name</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($rows2 as $record): ?>  
                <tr>
                  <td><?php echo  $record[0] ?></td>
                  <td><?php echo  $record[1] ?></td>
                </tr>
                <?php endforeach; ?>  
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
