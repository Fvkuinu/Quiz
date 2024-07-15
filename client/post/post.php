<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../../login/login_form.html");
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
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php include('../../header.php'); ?>

    <h1>POST</h1>
    <p>
    <form action="post_submit.php" method="get">
        <h3>問題文</h3><br>
        <textarea name="question" rows="10" cols="40" required></textarea><br>
        <h3>答え</h3><br>
        <input type="text" name="answer" size="35" required><br>

        <input type="submit" value="送信" class="btn-square">
    </form>
    </p>


</body>

</html>