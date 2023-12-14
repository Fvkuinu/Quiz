<?php


session_start();
date_default_timezone_set("Asia/Tokyo");
if (isset($_GET["username"]) && isset($_GET["passwd"])) {
    $username = $_GET["username"];
    $passwd = $_GET["passwd"];
    //認証処理
    $pdo = new PDO("sqlite:../SQL/quiz.sqlite");
    $st = $pdo->prepare("select * from user where username=?");
    $st->execute(array($username));
    $user_on_db = $st->fetch();
    if (!$user_on_db) {
        $result = "指定されたユーザが存在しません。";
    } else if ($passwd == $user_on_db["password"]) {
        // 等しければ
        $_SESSION["user"] = ["id" => $user_on_db["id"],"name" => $username];
        $result = "ようこそ" . $username . "さん。ログインに成功しました。";
        // ユーザーが管理者の場合、管理画面にリダイレクト
        if ($user_on_db["id"]==1) {
            header("Location: ../admin/admin_panel.php");
            exit;
        }
    } else {
        // そうでなければ
        $result = "パスワードが違います。";
    }
}else {
    $result = "不正なアクセスです。";
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login success</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

        <h2>
            <?php print $result; ?>
        </h2>
        <p><a href="../home.php">ホームに戻る</a></p>

</body>

</html>