<?php
include "../Connection.php";

$Message = '';

if (isset($_POST["Submit"]))
{
  $Name = $_POST["Name"];
  $Email = $_POST["Email"];
  $Password = $_POST["Password"];
  $ConfirmPassword = $_POST["ConfirmPassword"];
  $Role = $_POST["Role"];

  if($Password == $ConfirmPassword)
  {
    $sql = "INSERT INTO `users`(`Name`, `Email`, `Password`, `Role`) VALUES ('$Name','$Email','$Password','$Role')";
  $Result = mysqli_query($conn, $sql);
  if(!$Result)
  {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  else
  { 
    $last_id = mysqli_insert_id($conn);
    header("Location: ../Home/index.php?UserId=$last_id");
    exit(); // Stop script execution after a redirect
  }
  }else
  {
    $Message = "Passwords do not match";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <title>SignUp</title>
  </head>
  <body>
    <div class="container">
      <div class="contentSignUp">
        <div class="header">
          <p class="header">Sign Up</p>
          <p class="title">please fill in this form to create an account</p>
        </div>
        <div class="body">
          <form action="index.php" method="post">
            <input
              type="text"
              id="name"
              name="Name"
              placeholder="name"
              required
              class="name"
            />
            <input
              type="email"
              id="email"
              name="Email"
              placeholder="email"
              required
              class="email"
            />
            <div class="password">
              <input
                type="password"
                id="password"
                name="Password"
                placeholder="password"
                required
                class="password"
              />
              <i class="fa-solid fa-eye"></i>
            </div>
            <div class="confirmPassword">
              <input
                type="password"
                id="confirmPassword"
                name="ConfirmPassword"
                placeholder="confirm password"
                required
                class="confirmPassword"
              />
              <i class="fa-solid fa-eye"></i>
            </div>

            <div class="role">
              <label for="role" class="role">Role</label>
              <select name="Role" id="role">
                <option value="Teacher">Teacher</option>
                <option value="Student">Student</option>
              </select>
            </div>
            <p class="text-titleError">
              <h2 style="color : White;"><?php echo $Message; ?></h2>
            </p>
            <div class="btns">
              <!-- <button class="a signIn" type="submit">sign in</button> -->
              <a class="a signIn" href="../index.php">Sign in</a>
              <!-- <a class="submit" href="#">submit</a> -->
              <button type="submit" name="Submit" class="a submit">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="js/main.js"></script>
  </body>
</html>
