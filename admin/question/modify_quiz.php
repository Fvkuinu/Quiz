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
// データベース接続情報
$pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$id = isset($_GET['id']) ? $_GET['id'] : '';

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $updateStmt = $pdo->prepare("UPDATE question SET question = :question, answer = :answer WHERE id = :id");
    $updateStmt->bindParam(':question', $question);
    $updateStmt->bindParam(':answer', $answer);
    $updateStmt->bindParam(':id', $id);
    $updateStmt->execute();

    header('Location: edit_quiz.php');
    exit;
}

// 指定されたIDの情報を取得
$stmt = $pdo->prepare("SELECT * FROM question WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$question = $stmt->fetch();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Question</title>
    <link rel="stylesheet" href="../..//CSS/style.css">
</head>
<body>
    <?php include('../admin_header.php'); ?>
    <h1>MODIFY QUIZ</h1>
    <form method="post">
        <h3>問題</h3>
        <input type="text" name="question" value="<?php echo $question['question']; ?>" required>
        <h3>回答</h3>
        <textarea name="answer" required><?php echo $question['answer']; ?></textarea><br>
        <button type="submit" class="btn-square">更新</button>
    </form>
</body>
</html>
