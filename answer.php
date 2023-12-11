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



    // ユーザーがまだ解いていない問題をランダムに一つ取得するSQLクエリ
    $sql = "SELECT answer FROM question WHERE id = :questionId";
    $pdo = new PDO("sqlite:SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $st = $pdo->prepare($sql);
    $st->execute([':questionId' => $questionId]);
    $correctAnswer = $st->fetchColumn();
    // 答え合わせ
    if ($correctAnswer !== false) {
        $isCorrect = $user_answer === $correctAnswer;
        if ($isCorrect) {
            $result = "正解です！";
        } else {
            $result = "不正解です。正解は " . h($correctAnswer) . " です。";
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
        $result = "問題が見つかりませんでした。";
    }


}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>答え合わせ</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
    <p><a href="home.php">ホーム</a></p>
    <p><a href="start.php">クイズを解く</a></p>
    <p><a href="post.php">投稿する</a></p>

    <?php
    if (isset($_SESSION["user"])) {
        //ユーザ認証済みのときの処理
        print '<p><a href="profile.php">' . h($_SESSION["user"]["name"]) . '</a></p>';
        print '<p>[<a href="logout.php">ログアウト</a>]</p>';
    } else {
        //未認証のときの処理
        print '<p>[<a href="login_form.php">ログイン</a>]</p>';
    }
    ?>

    <?php
    print '<p>' . $result . '</p>';
    ?>




</body>

</html>