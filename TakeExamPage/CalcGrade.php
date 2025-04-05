<?php
include "../Connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ExamId = intval($_POST['ExamId']);
    $UserId = intval($_POST['UserId']);
    $userAnswers = $_POST['answers']; 

    $score = 0;
    $totalQuestions = count($userAnswers);

    $sql = "SELECT Id, RightAnswer FROM questions WHERE ExamId = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $ExamId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $RightAnswer = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $RightAnswer[$row['Id']] = $row['RightAnswer'];
    }

    mysqli_stmt_close($stmt);
    foreach ($userAnswers as $questionId => $userAnswer) {
        if (isset($RightAnswer[$questionId]) && $RightAnswer[$questionId] === $userAnswer) {
            $score++;
        }
    }

    $percentage = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;

    header("Location: Calculate/index.php?UserId=$UserId&score={$score}&percentage={$percentage}");
    exit;
}
?>
