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
$pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
// 指定されたIDの情報を取得
$id = isset($_GET['id']) ? $_GET['id'] : '';
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM information WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $info = $stmt->fetch();
}

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $time = date("Y-m-d H:i");
    $updateStmt = $pdo->prepare("UPDATE information SET title = :title, content = :content, updated_at = :updated_at WHERE id = :id");
    $updateStmt->bindParam(':title', $title, PDO::PARAM_STR);
    $updateStmt->bindParam(':content', $content, PDO::PARAM_STR);
    $updateStmt->bindParam(':updated_at', $time, PDO::PARAM_STR);
    $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $updateStmt->execute();

    // 更新後、リダイレクトなどの処理を行う
    header('Location: edit_information.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Information</title>
    <link rel="stylesheet" href="../..//CSS/style.css">
</head>

<body>
    <?php include('../admin_header.php'); ?>
    <h1>MODIFY INFORMATION</h1>
    <form method="post">
            <h3>タイトル</h3>
            <input type="text" id="title" name="title" value="<?php echo h($info['title']); ?>" required>
            <h3>内容</h3>
            <textarea id="content" name="content" required><?php echo h($info['content']); ?></textarea><br>
        <button type="submit" class="btn-square" >更新</button>
    </form>
</body>
</html>