<?php
session_start();

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}


?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>プロフィール</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php include '../../header.php' ?>

    <?php
    if(isset($_GET['userId'])){
        $userId = $_GET['userId'];
        // ユーザー情報の取得
        $pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
        $stmtUser = $pdo->prepare("SELECT username FROM user WHERE id = ?");
        $stmtUser->execute([$userId]);
        $user = $stmtUser->fetch();
        if ($user) {
            // ユーザー名の表示
            echo "<h1>" . h($user['username']) . "'s Profile</h1>";
    
            // user_answer テーブルから問題数と正解数の取得
            $stmtAnswers = $pdo->prepare("SELECT COUNT(*) as solved_problems, SUM(is_correct) as correct_answers FROM user_answer WHERE user_id = ?");
            $stmtAnswers->execute([$userId]);
            $answers = $stmtAnswers->fetch();
    
            $solvedProblems = $answers['solved_problems'];
            $correctAnswers = $answers['correct_answers'];
    
            // 情報の表示

            echo "<table>";
            echo "<tr><th>解いた問題の数</th><th>正解数</th><th>正解率</th></tr>"; // ヘッダ行
            
            echo "<tr>";
            echo "<td>" . h($solvedProblems)  . "</td>"; // 順位
            echo "<td>" .h($correctAnswers)."</td>"; // ユーザーネーム
            
    
            // 正解率の計算と表示
            if ($solvedProblems > 0) {
                $accuracy = ($correctAnswers / $solvedProblems) * 100;
                echo "<td>" . h(number_format($accuracy, 2)) . "%</td>";
            } else {
                echo "<td>正解率: N/A</td>";
            }
        } else {
            echo "<p>指定されたユーザーは存在しません。</p>";
        }
    } else {
        echo "<p>無効なユーザーIDです。</p>";
    }
    echo "</tr>";
    ?>



</body>

</html>