<?php
include "../Connection.php"; // Assumes $conn is defined here

$Message = '';

if (isset($_GET['UserId'])) {
    $Id = $_GET['UserId'];
    
    // Prepare SQL query with placeholders
    $sql = "SELECT * FROM `users` WHERE `Id` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $Id);

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
        $UserRole = $row['Role'];
    }
}

if (isset($_POST['Submit'])) {
    $Id = $_POST['ExamId'];
    
    // Prepare SQL query with placeholders
    $sql02 = "SELECT * FROM `exams` WHERE `Id` = ?";
    $stmt02 = mysqli_prepare($conn, $sql02);
    mysqli_stmt_bind_param($stmt02, "i", $Id);

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
        $last_id02 = $row02['Id'];

        // Redirect to the page with the user's ID in the URL
        header("Location: ../TakeExamPage/index.php?ExamId=$last_id02&UserId=$last_id");
        exit(); // Stop script execution after a redirect
    } else {
        // Set the error message for incorrect credentials
        $Message = "There isn't an Exam with that id.";
    }
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
    <link rel="stylesheet" href="css/style02.css" />
    <link rel="stylesheet" href="../Home/css/all.min.css" />
    <title>Exam Actions</title>
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
                    <h2><?php echo htmlspecialchars($row['Name']); ?></h2>
                </li>
                <li>
                    <br>
                    <h2><?php echo htmlspecialchars($row['Role']); ?></h2>
                </li>
                <li>
                    <a href="../Home/index.php?UserId=<?php echo $row['Id']; ?>">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span>dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="../ExamActionPage/index.php?UserId=<?php echo $row['Id']; ?>">
                        <i class="fa-brands fa-steam"></i>
                        <span>Exams</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <?php if ($UserRole == "Student"): ?>
                <div style="gap : 50px;" class="takeExam">
                    <p class="titalHeader">take exam</p>
                    <div class="infoForCreatExam">
                        <i class="fa-solid fa-plane-departure"></i>
                    </div>
                </div>
            <?php else: ?>
                        <a class="creatExam" style="flex-grow : 1; width : 250px; height : 250px; display : flex; flex-direction: column;   align-items: center; justify-content: center; gap: 50px; text-decoration : none;   border-radius: 20px; " href="../CreateExamPage/CreateExam/index.php?UserId=<?php echo $last_id; ?>">
                          <p style="color : white; font-size : 30px; text-transform : capitalize;" class="titalHeader">create exam</p>
                          <i style="color : white; font-size : 30px;" class="fa-solid fa-laptop-file"></i>
                        </a>
            <?php endif; ?>
            <div class="registExam">
                <form action="" method="post">
                    <i class="close fa-solid fa-xmark"></i>
                    <label for="examId">exam id:</label>
                    <input required id="examId" class="examId" name="ExamId" type="number" min="1" />
                    <!--if there isn't an Exam with that id-->
                    <p class="text-titleError">
                        <h4 style="color:white;"><?php echo $Message; ?></h4>
                    </p>
                    <button type="submit" name="Submit">regist</button>
                </form>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/main.js"></script>
</body>
</html>