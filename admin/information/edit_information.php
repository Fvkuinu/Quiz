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
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>admin_panel</title>
    <link rel="stylesheet" href="../..//CSS/style.css">
</head>

<body>
    <?php include('../admin_header.php'); ?>
    <h1>EDIT INFORMATION</h1>
    <a href="add_information.php" class="btn-square">Add information</a>
    <?php
    // ページネーション設定
    $itemsPerPage = 10; // 1ページあたりのアイテム数
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $itemsPerPage;

    // データを取得
    $pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $stmt = $pdo->prepare("SELECT * FROM information ORDER BY id DESC LIMIT :offset, :itemsPerPage");
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $informations = $stmt->fetchAll();

    // データの表示
    foreach ($informations as $info) {
        echo "<div class='box26'><span class='box-title'>{$info['title']}</span>";
        echo "<p>{$info['content']}</p>";
        // 編集・削除リンク
        echo "<a class='btn-square' href='modify_information.php?id={$info['id']}'>Modify</a> | <a  class='btn-square' href='delete_information.php?id={$info['id']}' onclick='return confirmDelete()'>Delete</a></div>";
    }

    // ページネーションリンク
    $totalItems = $pdo->query("SELECT COUNT(*) FROM information")->fetchColumn();
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