<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login_form.php");
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
    <title>問題投稿</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
    <?php include('header.php'); ?>

    <h2>問題を作成</h2>
    <p>
    <form action="post_submit.php" method="get">
        問題文<br>
        <textarea name="question" rows="10" cols="40" required></textarea><br>
        答え<br>
        <input type="text" name="answer" size="40" required><br>

        <input type="submit" value="送信">
    </form>
    </p>


</body>

</html>