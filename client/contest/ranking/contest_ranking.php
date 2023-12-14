<?php
session_start();

// ユーザーがログインしていなければログインページへリダイレクト
if (!isset($_SESSION["user"])) {
    header("Location: ../../../login/login_form.html");
    exit;
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

date_default_timezone_set("Asia/Tokyo");
$pdo = new PDO("sqlite:../../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// コンテストIDの取得
$contest_id = isset($_GET['contest_id']) ? (int) $_GET['contest_id'] : 0;

// コンテストの詳細を取得
$contestStmt = $pdo->prepare("SELECT * FROM contest WHERE id = :contest_id");
$contestStmt->bindParam(':contest_id', $contest_id);
$contestStmt->execute();
$contest = $contestStmt->fetch(PDO::FETCH_ASSOC);

// 問題の数とタイトルを取得
$questionQuery = "SELECT id, question_title FROM contest_question WHERE contest_id = :contestId ORDER BY question_order";
$questionStmt = $pdo->prepare($questionQuery);
$questionStmt->bindParam(':contestId', $contest_id, PDO::PARAM_INT);
$questionStmt->execute();
$questions = $questionStmt->fetchAll(PDO::FETCH_ASSOC);

// ページネーションの設定
$perPage = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $perPage;

// ランキングとスコアの取得
$query = "SELECT u.id as user_id, u.username, SUM(IFNULL(cq.point, 0)) as total_score,
          RANK() OVER (ORDER BY SUM(IFNULL(cq.point, 0)) DESC) as rank
          FROM user u
          JOIN contest_answer ca ON u.id = ca.user_id
          LEFT JOIN contest_question cq ON ca.question_id = cq.id AND ca.is_correct = 1
          WHERE ca.contest_id = :contestId AND u.id != 1
          GROUP BY u.id
          ORDER BY total_score DESC
          LIMIT :start, :perPage";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':contestId', $contest_id, PDO::PARAM_INT);
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>コンテスト問題</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php include '../../..header.php' ?>
    <?php include '../contest_header.php' ?>
    <h1>
        <?php echo h($contest['name']); ?>
    </h1>
    <h2>ランキング</h2>
    <?php
    // 結果の表示
    echo "<table border='1'>";
    echo "<tr><th>順位</th><th>ユーザー名</th>";

    foreach ($questions as $question) {
        echo "<th>" . h($question['question_title']) . "</th>";
    }
    echo "<th>総得点</th></tr>";

    foreach ($ranking as $row) {
        echo "<tr>";
        echo "<td>" . h($row['rank']) . "</td>";
        echo "<td><a href='../../client/profile/profile.php?userId=" . h($row['user_id']) . "'>". h($row['username']) . "</a></td>";

        foreach ($questions as $question) {
            $scoreQuery = "SELECT IFNULL(MAX(cq.point), 0) as score
                       FROM contest_question cq
                       LEFT JOIN contest_answer ca ON cq.id = ca.question_id
                       WHERE ca.user_id = (SELECT id FROM user WHERE username = :username) 
                       AND cq.id = :questionId AND ca.is_correct = 1";
            $scoreStmt = $pdo->prepare($scoreQuery);
            $scoreStmt->bindParam(':username', $row['username'], PDO::PARAM_STR);
            $scoreStmt->bindParam(':questionId', $question['id'], PDO::PARAM_INT);
            $scoreStmt->execute();
            $scoreResult = $scoreStmt->fetch(PDO::FETCH_ASSOC);
            echo "<td>" . h($scoreResult['score']) . "</td>";
        }

        echo "<td>" . h($row['total_score']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    ?>

</body>

</html>