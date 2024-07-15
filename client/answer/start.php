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
    <link rel="stylesheet" href="../..//CSS/style.css">
</head>

<body>
    <?php include '../../header.php' ?>
    <h1>QUIZ</h1>
    <div id=text>
        <p id=quiz>スタートボタンを押すと問題が出題されます<br>
        解答は、英数、ひらがな、カタカナのみ入力できます。<br>
        各問題の制限時間は30秒です</p><br>
        <button onclick="location.href='solve.php'" class="btn-square">スタート</button>
    </div>

</body>

</html>