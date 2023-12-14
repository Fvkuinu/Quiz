<?php
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

// データベースへの接続
$pdo = new PDO("sqlite:../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>プロフィール</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 既存のユーザー名が存在するかチェック
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // ユーザー名が既に存在する場合
            echo "このユーザー名は既に存在します";
            echo "<p><a href='register_form.html'>ユーザー登録画面へ</a></p>";
            echo "<p><a href='login_form.html'>ログイン画面へ</a></p>";
        } else {
            // 新しいユーザーを登録
            $stmt = $pdo->prepare("INSERT INTO user (username, password, rating) VALUES (?, ?, ?)");
            $stmt->execute([$username, $password, 0]);

            echo "登録されました";
            echo "<p><a href='login_form.html'>ログイン画面へ</a></p>";
        }
    }
    ?>



</body>

</html>