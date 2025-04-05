<?php
include "Connection.php"; // Assumes $conn is defined here

// Initialize $Message with a default empty value
$Message = '';

if (isset($_POST["Submit"])) {
    // Retrieve and sanitize input
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];

    // Prepare SQL query with placeholders
    $sql = "SELECT `Id`, `Email`, `Password` FROM `users` WHERE `Email` = ? AND `Password` = ?";

    // Initialize a statement
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Bind parameters to the statement
    mysqli_stmt_bind_param($stmt, "ss", $Email, $Password);

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

        // Redirect to the page with the user's ID in the URL
        header("Location: Home/index.php?UserId=$last_id");
        exit(); // Stop script execution after a redirect
    } else {
        // Set the error message for incorrect credentials
        $Message = "Your email or password is incorrect or you don't have an account.";
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="SignIn/css/style.css" />
    <link rel="stylesheet" href="SignIn/css/all.min.css" />
    <title>SignIn</title>
  </head>
  <body>
    <div class="content">
      <div class="header">
        <p class="text-header">login</p>
        <p class="text-title">please fill in this form to regester</p>
      </div>
      <div class="body">
        <form action="index.php" method="post">
          <input
            name="Email"
            type="text"
            placeholder="Email"
            required
            class="email"
          />
          <div class="div-password">
            <input
              name="Password"
              type="password"
              placeholder="password"
              required
              class="password"
            />
            <i class="fa-solid fa-eye"></i>
          </div>
          <!--if user don't have an account-->
          <p class="text-titleError">
            <?php echo $Message; ?>
          </p>
          <div class="btns">
            <a href="SignUp/index.php" class="a signIn">sign up</a>
            <!-- <a href="#" class="a submit">submit</a> -->
            <button type="submit" name="Submit" class="a submit">submit</button>
          </div>
        </form>
      </div>
    </div>
    <script src="SignIn/js/main.js"></script>
  </body>
</html>
