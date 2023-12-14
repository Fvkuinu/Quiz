<?php
session_start();

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>問題投稿</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php include '../../header.php' ?>

    <p>スタートボタンを押すと問題が出題されます</p>
    <p>解答は、英数、ひらがな、カタカナのみ入力できます。</p>
    <p>各問題の制限時間は30秒です</p>
    <button onclick="location.href='solve.php'">スタート</button>


</body>

</html>