<?php
$UserId = isset($_GET['UserId']) ? intval($_GET['UserId']) : 0;
$score = isset($_GET['score']) ? intval($_GET['score']) : 0;
$percentage = isset($_GET['percentage']) ? floatval($_GET['percentage']) : 0.0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.min.css" />
    <title>Result</title>
</head>
<body>
    <div class="container">
        <a href="../../Home/index.php?UserId=<?php echo $UserId ?>"><i class="fa-solid fa-xmark"></i></a>
        <div class="content">
            <p class="degreeTitle">Your score is: <?php echo $score; ?> out of <?php echo round($percentage, 2); ?>%</p>
        </div>
    </div>
</body>
</html>
