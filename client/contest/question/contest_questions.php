<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../../../login/login_form.php");
    exit;
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set("Asia/Tokyo");
// データベース接続情報
$pdo = new PDO("sqlite:../../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$contest_id = isset($_GET['contest_id']) ? (int) $_GET['contest_id'] : 0;

try {
    // コンテストの詳細を取得
    $contestStmt = $pdo->prepare("SELECT * FROM contest WHERE id = :contest_id");
    $contestStmt->bindParam(':contest_id', $contest_id);
    $contestStmt->execute();
    $contest = $contestStmt->fetch(PDO::FETCH_ASSOC);

    // コンテストの問題を取得
    $questionStmt = $pdo->prepare("SELECT * FROM contest_question WHERE contest_id = :contest_id");
    $questionStmt->bindParam(':contest_id', $contest_id);
    $questionStmt->execute();
    $questions = $questionStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("エラー: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>コンテスト問題</title>
</head>

<body>
    <?php include '../../../header.php' ?>
    <?php include '../contest_header.php' ?>
    <h1>
        <?php echo h($contest['name']); ?>
    </h1>
    <h2>問題一覧</h2>
    <ul>
        <?php
        // 現在の時間を取得
        $currentTime = new DateTime();

        foreach ($questions as $question) {
            // コンテストの開始時間をDateTimeオブジェクトに変換
            $startTime = new DateTime($contest['start_time']);

            // コンテストが開始されている場合のみリンクを表示
            if ($currentTime >= $startTime) {
                $link = "../../../contest_question.php?contest_id={$contest_id}&question_order={$question['question_order']}";
                echo "<li><a href='{$link}'>" . h($question['question_title']) . "</a></li>";
            } else {
                // コンテストが開始していない場合
                echo "<li>公開時間になっていません</li>";
                break;
            }
        }
        ?>

    </ul>
</body>

</html>