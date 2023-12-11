<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login_form.php");
    exit;
}
if (!$_SESSION["user"]["id"] == 1) {
    header("Location: home.php");
    exit;
}
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set("Asia/Tokyo");
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>admin_panel</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
    <p><a href="admin_panel.php">adminトップへ</a></p>
    <?php
    $itemsPerPage = 5; // 1ページあたりのアイテム数
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $itemsPerPage;

    // データベース接続情報
    $pdo = new PDO("sqlite:SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    // データを取得
    $stmt = $pdo->prepare("SELECT * FROM question ORDER BY id DESC LIMIT :offset, :itemsPerPage");
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll();

    // データの表示
    foreach ($questions as $question) {
        echo "<p>{$question['question']}</p>";
        echo "<div>回答: {$question['answer']}</div>";
        echo "<a href='modify_quiz.php?id={$question['id']}'>Modify</a> | <a href='delete_quiz.php?id={$question['id']}' onclick='return confirmDelete()'>Delete</a>";
    }

    // ページネーションリンク
    $totalItems = $pdo->query("SELECT COUNT(*) FROM question")->fetchColumn();
    $totalPages = ceil($totalItems / $itemsPerPage);

    echo "<div>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<span><a href='?page=$i'>$i</a> </span>";
    }
    echo "</div>";
    ?>

    <!-- 削除するか確認用 -->
    <script>
        function confirmDelete() {
            return confirm("本当に削除しますか？");
        }
    </script>


</body>

</html>