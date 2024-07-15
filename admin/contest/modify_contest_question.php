<?php
session_start();

// セッションチェック
if (!isset($_SESSION["user"])) {
    header("Location: ../../login/login_form.php");
    exit;
}
if ($_SESSION["user"]["id"] != 1) {
    header("Location: ../../home.php");
    exit;
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

date_default_timezone_set("Asia/Tokyo");
$pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// GETメソッドで送信されたパラメータを取得
$question_id = isset($_GET['question_id']) ? $_GET['question_id'] : '';

// 指定された question_id のレコードを取得
if ($question_id !== '') {
    $stmt = $pdo->prepare("SELECT * FROM contest_question WHERE id = :question_id");
    $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    $stmt->execute();
    $question = $stmt->fetch();
}

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // フォームから送信されたデータを取得
    $contest_id = $_POST['contest_id'];
    $question_title = $_POST['question_title'];
    $point = $_POST['point'];
    $correct_answer = $_POST['correct_answer'];
    $time = date("Y-m-d H:i");

    // レコードを更新
    $updateStmt = $pdo->prepare("UPDATE contest_question SET question_title = :question_title, point = :point, correct_answer = :correct_answer, updated_at = :updated_at WHERE id = :question_id");
    $updateStmt->bindParam(':question_title', $question_title, PDO::PARAM_STR);
    $updateStmt->bindParam(':point', $point, PDO::PARAM_INT);
    $updateStmt->bindParam(':correct_answer', $correct_answer, PDO::PARAM_STR);
    $updateStmt->bindParam(':updated_at', $time, PDO::PARAM_STR);
    $updateStmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
    $updateStmt->execute();

    // 更新後の処理（リダイレクト）
    header('Location: edit_contest_question.php?id=' . $contest_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>Question Edit</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>
<body>
    <?php include('../admin_header.php'); ?>
    <h1>MODIFY QUIZ</h1>
    <form method="post">
        <input type="hidden" name="contest_id" value="<?php echo h($question['contest_id']); ?>" required>
            <h3>問題タイトル</h3>
            <input type="text" id="question_title" name="question_title" value="<?php echo isset($question['question_title']) ? h($question['question_title']) : ''; ?>"required>

            <h3>得点</h3>
            <input type="number" id="point" name="point" value="<?php echo isset($question['point']) ? h($question['point']) : ''; ?>" required>

            <h3>正解</h3>
            <input type="text" id="correct_answer" name="correct_answer" value="<?php echo isset($question['correct_answer']) ? h($question['correct_answer']) : ''; ?>" required><br>
        <button type="submit" class="btn-square">更新</button>
    </form>
</body>
</html>
