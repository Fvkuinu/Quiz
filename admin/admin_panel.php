<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../login/login_form.php");
    exit;
}
if ($_SESSION["user"]["id"] != 1) {
    header("Location: ../home.php");
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
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body>
    <?php include 'admin_header.php' ?>

    <div id=text><h1>管理画面</h1>
    <a href="./information/edit_information.php"  class="btn-partial-line"><i class="fa fa-caret-right"></i> お知らせを編集する</a>
    <a href="./question/edit_quiz.php"  class="btn-partial-line"><i class="fa fa-caret-right"></i> クイズを編集する</a>
    <a href="./contest/edit_contest.php"  class="btn-partial-line"><i class="fa fa-caret-right"></i> コンテストを編集する</a>
    </div>


</body>

</html>