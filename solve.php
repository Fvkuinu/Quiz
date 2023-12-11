<?php
session_start();

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

if (isset($_SESSION["user"])) {



    // ユーザーIDを設定
    $userId = $_SESSION["user"]["id"];

    // ユーザーがまだ解いていない問題をランダムに一つ取得するSQLクエリ
    $sql = "SELECT * FROM question WHERE id NOT IN (
            SELECT question_id FROM user_answer WHERE user_id = :userId
        ) ORDER BY RANDOM() LIMIT 1";
    $pdo = new PDO("sqlite:SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $st = $pdo->prepare($sql);
    $st->execute(['userId' => $userId]);
    $question = $st->fetch();


} else {
    //未認証のときの処理
    //print '<p>[<a href="login_form.php">ログイン</a>]</p>';
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>問題投稿</title>
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
    
    <!-- 残り時間を表示する -->
    <p id="remainingTime">残り時間：30秒</p>
    <?php
    if ($question) {
        // 問題が見つかった場合の処理
        echo '<h2>問題</h2>';
        echo "<p>Question: " . $question['question'];
        echo '<form id="quizForm" action="answer.php" method="get">
        <input type="text" name="answer" />
        <input type="hidden" name="questionId" value='.$question['id'].'>
        <button type="submit">回答を送信</button>
        </form>';
        echo '<script src="JS/quiz_timer.js"></script>';
    } else {
        // 問題が見つからなかった場合の処理
        echo "All questions have been answered.";
        
    }
    ?>


</body>

</html>