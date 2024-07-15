<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../../login/login_form.php");
    exit;
}
if ($_SESSION["user"]["id"] != 1) {
    header("Location: ../../home.php");
    exit;
}
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set("Asia/Tokyo");
?>
<?php

// データベース接続情報
$pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$perPage = 10; // 1ページあたりの表示件数
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

$contest_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

try {
    // 特定のコンテストに属する問題の総数を取得
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM contest_question WHERE contest_id = :contest_id");
    $stmt->bindParam(':contest_id', $contest_id, PDO::PARAM_INT);
    $stmt->execute();
    $total = $stmt->fetchColumn();

    if ($total > 0) {
        // コンテストに属する問題を取得
        $stmt = $pdo->prepare("SELECT * FROM contest_question WHERE contest_id = :contest_id ORDER BY question_order LIMIT :start, :perPage");
        $stmt->bindParam(':contest_id', $contest_id, PDO::PARAM_INT);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include '../admin_header.php';
        echo "<h1>EDIT QUIZ</h1>";
        // 問題の表示
        foreach ($questions as $question) {
            echo "<div class='box26'>\n";
            echo "<span class='box-title'>" . h($question['question_title']) . "</span>\n";
            echo "<p>番号: " . h($question['question_order']) . "</p>\n";
            echo "<p>点数: " . h($question['point']) . "</p>\n";
            // 削除リンク
            echo "<a class='btn-square' href='delete_contest_question.php?question_id=" . $question['id'] . "' onclick='return confirmDelete()'>Delete</a> | ";
            echo "<a class='btn-square' href='modify_contest_question.php?question_id=" . $question['id'] . "'>Modify</a>";
            echo "</div>\n";
        }

        // ページネーションのリンク
        $totalPages = ceil($total / $perPage);
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='?contest_id=" . $contest_id . "&page=" . $i . "'>" . $i . "</a> ";
        }
    } else {
        echo '<div id=error>問題が存在しません</div><a href="edit_contest.php">コンテスト一覧へ戻る</a>';
    }


} catch (PDOException $e) {
    echo 'エラー: ' . $e->getMessage();
}
?>
<script>
    function confirmDelete() {
        return confirm("本当に削除しますか？");
    }
</script>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>コンテスト問題編集</title>
    <link rel="stylesheet" href="../..//CSS/style.css">
</head>

<body></body>

</html>