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

    <?php
    // ページネーション設定
    $itemsPerPage = 10; // 1ページあたりのアイテム数
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $itemsPerPage;
    
    // データを取得
    $pdo = new PDO("sqlite:SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $stmt = $pdo->prepare("SELECT * FROM information LIMIT :offset, :itemsPerPage");
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $informations = $stmt->fetchAll();

    // データの表示
    foreach ($informations as $info) {
        echo "<p>{$info['title']}</p>";
        echo "<div>{$info['content']}</div>";
        // 編集・削除リンク（編集・削除機能は別途実装が必要）
        echo "<a href='edit.php?id={$info['id']}'>Edit</a> | <a href='delete.php?id={$info['id']}'>Delete</a>";
    }

    // ページネーションリンク
    $totalItems = $pdo->query("SELECT COUNT(*) FROM information")->fetchColumn();
    $totalPages = ceil($totalItems / $itemsPerPage);

    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=$i'>$i</a> ";
    }
    ?>



</body>

</html>