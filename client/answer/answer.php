<?php
session_start();

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set("Asia/Tokyo");
if (isset($_GET['answer']) && isset($_GET['questionId'])) {
    $questionId = $_GET['questionId'];
    $user_answer = $_GET['answer'];



    
    $sql = "SELECT answer FROM question WHERE id = :questionId";
    $pdo = new PDO("sqlite:../..//SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $st = $pdo->prepare($sql);
    $st->execute([':questionId' => $questionId]);
    $correctAnswer = $st->fetchColumn();
    // 答え合わせ
    if ($correctAnswer !== false) {
        $isCorrect = $user_answer === $correctAnswer;
        if ($isCorrect) {
            $result = "<h2>正解です！</h2>";
        } else {
            $result = "<div id=error>不正解です。正解は " . h($correctAnswer) . " です。</div>";
        }
        if (isset($_SESSION["user"])) {
            $insertSql = "INSERT INTO user_answer (user_id, question_id, answer,answered_at, is_correct) VALUES (:userId, :questionId, :answer, :answered_at, :isCorrect)";
            $time = date("Y-m-d H:i");
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->bindParam(':userId', $_SESSION["user"]["id"], PDO::PARAM_INT);
            $insertStmt->bindParam(':questionId', $questionId, PDO::PARAM_INT);
            $insertStmt->bindParam(':answer', $user_answer, PDO::PARAM_STR);
            $insertStmt->bindParam(':answered_at', $time, PDO::PARAM_STR);
            $insertStmt->bindParam(':isCorrect', $isCorrect, PDO::PARAM_BOOL);
            $insertStmt->execute();
        }
    } else {
        $result = "<div id=error>問題が見つかりませんでした。</div>";
    }


}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>答え合わせ</title>
    <link rel="stylesheet" href="../..//CSS/style.css">
</head>

<body>
    <?php include('../../header.php'); ?>

    <?php
    print  $result ;
    ?>
    <p><a href='solve.php' class="btn-partial-line"><i class="fa fa-caret-right"></i>つぎの問題へ</a></p>
    
    




</body>

</html>