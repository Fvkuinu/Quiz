<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login_form.php");
    exit;
}
if ($_SESSION["user"]["id"] != 1) {
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
    <?php include 'admin_header.php' ?>

    <h2>管理画面</h2>
    <p><a href="edit_information.php">お知らせを編集する</a></p>
    <p><a href="edit_quiz.php">クイズを編集する</a></p>
    <p><a href="edit_contest.php">コンテストを編集する</a></p>



</body>

</html>