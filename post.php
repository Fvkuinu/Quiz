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
    <p><a href="home.php">ホーム</a></p>
    <p><a href="start.php">クイズを解く</a></p>
    <p><a href="post.php">投稿する</a></p>

    <?php
    if (isset($_SESSION["user"])) {
        //ユーザ認証済みのときの処理
        print '<p><a href="profile.php">' . h($_SESSION["user"]["name"]) . '</a></p>';
        print '<p>[<a href="logout.php">ログアウト</a>]</p>';
    } else {
        //未認証のときの処理
        print '<p>[<a href="login_form.php">ログイン</a>]</p>';
    }
    ?>

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