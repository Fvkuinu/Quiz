<?php
session_start();

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set("Asia/Tokyo");
$pdo = new PDO("sqlite:SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$itemsPerPage = 5; // 1ページあたりのアイテム数
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

$st = $pdo->prepare("SELECT * FROM information ORDER BY id DESC LIMIT :offset, :itemsPerPage");
$st->bindParam(':offset', $offset, PDO::PARAM_INT);
$st->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$st->execute();
$data = $st->fetchAll();

// 総アイテム数と総ページ数を取得
$totalItems = $pdo->query("SELECT COUNT(*) FROM information")->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>home</title>
    <link rel="stylesheet" href="CSS/style.css">

</head>

<body>
    <p><a href="home.php">ホーム</a></p>
    <p><a href="start.php">クイズを解く</a></p>
    <p><a href="post.php">投稿する</a></p>

    <?php

    if (isset($_SESSION["user"])) {
        //ユーザ認証済みのときの処理
        print '<p><a href="profile.php?userId=' . $_SESSION["user"]["id"] . '">' . h($_SESSION["user"]["name"]) . '</a></p>';
        print '<p>[<a href="logout.php">ログアウト</a>]</p>';
    } else {
        //未認証のときの処理
        print '<p>[<a href="login_form.php">ログイン</a>]</p>';
    }
    ?>

    <?php
    foreach ($data as $info) {
        print '<div>';
        print '<p>投稿日:' . h($info["created_at"]);
        print '<h2>' . h($info["title"]) . '</h2>';
        print '<p>' . h($info["content"]) . '</p>';
        print '<p>最終更新日:' . h($info["updated_at"]);
        print '</div>';
    }
    ?>
    <!-- ページネーションリンク -->
    <div>
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<a href="?page=' . $i . '">' . $i . '</a> ';
        }
        ?>
    </div>
</body>

</html>