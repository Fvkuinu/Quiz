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
if (isset($_GET["question"]) && isset($_GET["answer"])) {
    $question = $_GET["question"];
    $answer = $_GET["answer"];
    $time = date("Y-m-d H:i");
    $pdo = new PDO("sqlite:SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $st = $pdo->prepare("INSERT INTO question(question, answer, writer_id) VALUES(?, ?, ?)");
    $st->execute(array($question, $answer, $_SESSION["user"]["id"]));


    $result = "投稿しました。";
} else {
    $result = "内容がありません。";
}
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

    <p>
        <?php echo $result; ?>
    </p>
    <p><a href="post.php">つづけて投稿をする</a></p>
    <p><a href="home.php">ホームに戻る</a></p>

</body>

</html>