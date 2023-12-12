<?php
session_start();

// ユーザーがログインしていなければログインページへリダイレクト
if (!isset($_SESSION["user"])) {
    header("Location: login_form.php");
    exit;
}
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set("Asia/Tokyo");
$userId = $_SESSION["user"]["id"]; // ログインユーザーのID

// データベース接続情報
$pdo = new PDO("sqlite:SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$contest_id = isset($_GET['contest_id']) ? (int)$_GET['contest_id'] : 0;

try {
    // ユーザーの提出結果を取得
    $submissionStmt = $pdo->prepare("SELECT * FROM submissions WHERE user_id = :user_id AND contest_id = :contest_id");
    $submissionStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $submissionStmt->bindParam(':contest_id', $contest_id, PDO::PARAM_INT);
    $submissionStmt->execute();
    $submissions = $submissionStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("エラー: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>提出結果</title>
</head>
<body>
    <h1>提出結果</h1>
    <table>
        <thead>
            <tr>
                <th>問題ID</th>
                <th>提出答え</th>
                <th>提出時間</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($submissions as $submission): ?>
                <tr>
                    <td><?php echo htmlspecialchars($submission['question_id']); ?></td>
                    <td><?php echo htmlspecialchars($submission['answer']); ?></td>
                    <td><?php echo htmlspecialchars($submission['submission_time']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
