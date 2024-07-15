<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../../login/login_form.php");
    exit;
}
if ($_SESSION["user"]["id"] != 1) {
    header("Location: ../../home.php");
    exit;
}
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set("Asia/Tokyo");
?>
<?php
// データベースへの接続
$pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $time = date("Y-m-d H:i:s");

    $insertStmt = $pdo->prepare("INSERT INTO information (title, content, created_at, updated_at) VALUES (:title, :content, :created_at, :updated_at)");
    $insertStmt->bindParam(':title', $title, PDO::PARAM_STR);
    $insertStmt->bindParam(':content', $content, PDO::PARAM_STR);
    $insertStmt->bindParam(':created_at', $time, PDO::PARAM_STR);
    $insertStmt->bindParam(':updated_at', $time, PDO::PARAM_STR);
    $insertStmt->execute();

    // 追加後、別のページにリダイレクト
    header('Location: edit_information.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <title>Add New Information</title>
    <link rel="stylesheet" href="../..//CSS/style.css">
</head>

<body>
<?php include '../admin_header.php' ?>
    <h1>ADD INFORMATION</h1>
    <form method="post">
            <h3>タイトル</h3>
            <input type="text" id="title" name="title" required>
            <h3>内容</h3>
            <textarea id="content" name="content" required></textarea><br>

        <button type="submit" class="btn-square" >追加</button>

        
    </form>
</body>

</html>