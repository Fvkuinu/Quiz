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
        $result = '<div id=error>指定されたユーザが存在しません。</div><a href="login_form.html" class="btn-partial-line"><i class="fa fa-caret-right"></i> ログインへ戻る</a>';
    } else if ($passwd == $user_on_db["password"]) {
        // 等しければ
        $_SESSION["user"] = ["id" => $user_on_db["id"],"name" => $username];
        $result = "<h2>ようこそ" . $username . "さん。ログインに成功しました。</h2>";
        // ユーザーが管理者の場合、管理画面にリダイレクト
        if ($user_on_db["id"]==1) {
            header("Location: ../admin/admin_panel.php");
            exit;
        }
    } else {
        // そうでなければ
        $result = '<div id=error>パスワードが違います。</div><a href="login_form.html" class="btn-partial-line"><i class="fa fa-caret-right"></i>ログインへ戻る</a>';
    }
}else {
    $result = '<div id=error>不正なアクセスです。</div><a href="login_form.html" class="btn-partial-line"><i class="fa fa-caret-right"></i>ログインへ戻る</a>';
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

            <?php print $result; ?>
        <p><a href="../home.php" class="btn-partial-line"><i class="fa fa-caret-right"></i>ホームに戻る</a></p>

</body>

</html>