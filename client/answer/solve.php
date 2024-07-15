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
    $pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $st = $pdo->prepare($sql);
    $st->execute(['userId' => $userId]);
    $question = $st->fetch();

    //問題が存在しないなら問題を自動で生成
    if (!$question){
        include 'chatapi.php';
    }

} else {
    //未認証のときの処理
    // ユーザーがまだ解いていない問題をランダムに一つ取得するSQLクエリ
    $sql = "SELECT * FROM question ORDER BY RANDOM() LIMIT 1";
    $pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $st = $pdo->prepare($sql);
    $st->execute();
    $question = $st->fetch();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>問題投稿</title>
    <link rel="stylesheet" href="../..//CSS/style.css">
</head>

<body>
    <?php include('../../header.php'); ?>

    <!-- 残り時間を表示する -->
    <p id="remainingTime">残り時間：30秒</p>
    <?php
    if ($question) {
        // 問題が見つかった場合の処理
        echo '<div id=text><h2>問題</h2>';
        echo "<p id=quiz>" . $question['question'];
        echo '<form id="quizForm" action="answer.php" method="get">
        <input type="text" name="answer" required/>
        <input type="hidden" name="questionId" value=' . $question['id'] . '>
        <br><button type="submit" class="btn-square">回答</button>
        </form></div>';
        echo "<script>
            let elapsedTime = 0; // 経過時間を記録する変数
            const intervalTime = 1000; // 更新間隔（1秒）
            const totalTime = 30000; // 合計時間（30秒）

            // 1秒ごとに経過時間を更新して表示する
            const intervalId = setInterval(function() {
                elapsedTime += intervalTime;

                // 残り時間（秒）を計算して表示
                let remainingTime = (totalTime - elapsedTime) / 1000;
                document.getElementById('remainingTime').textContent = '残り時間：' + remainingTime + '秒';

                // 30秒経過したらタイマーを停止してフォームを送信
                if (elapsedTime >= totalTime) {
                    clearInterval(intervalId);
                    document.getElementById('quizForm').submit();
                }
            }, intervalTime);
        </script>";
    } else {
        // 問題が見つからなかった場合の処理
        echo "<div id=text>All questions have been answered.</div>";

    }
    ?>


</body>

</html>