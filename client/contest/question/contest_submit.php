<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../../../login/login_form.html");
    exit;
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

date_default_timezone_set("Asia/Tokyo");
$pdo = new PDO("sqlite:../../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$contest_id = $_GET['contest_id'] ?? 0;
$question_order = $_GET['question_order'] ?? 0;
$answer_text = $_GET['answer'] ?? '';
$user_id = $_SESSION['user']['id'];
$submitted_at = date('Y-m-d H:i:s');

try {
    $stmt = $pdo->prepare("SELECT id, correct_answer FROM contest_question WHERE contest_id = :contest_id AND question_order = :question_order");
    $stmt->bindParam(':contest_id', $contest_id, PDO::PARAM_INT);
    $stmt->bindParam(':question_order', $question_order, PDO::PARAM_INT);
    $stmt->execute();
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($question) {
        $question_id = $question['id'];
        $is_correct = ($answer_text === $question['correct_answer']); // 正解判定

        $stmt = $pdo->prepare("INSERT INTO contest_answer (contest_id, user_id, question_id, answer_text, submitted_at, is_correct) VALUES (:contest_id, :user_id, :question_id, :answer_text, :submitted_at, :is_correct)");
        $stmt->bindParam(':contest_id', $contest_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindParam(':answer_text', $answer_text);
        $stmt->bindParam(':submitted_at', $submitted_at);
        $stmt->bindParam(':is_correct', $is_correct, PDO::PARAM_BOOL);
        $stmt->execute();
        
        //echo "回答が正常に送信されました。";
        header("Location: contest_submissions.php?contest_id=" . $contest_id);
        exit;
    } else {
        echo "問題が見つかりませんでした。";
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>コンテスト解説</title>
    <link rel="stylesheet" href="../../..//CSS/style.css">
</head>

<body>
</body>

</html>