<?php
session_start();

// ユーザーがログインしていなければログインページへリダイレクト
if (!isset($_SESSION["user"])) {
    header("Location: login_form.php");
    exit;
}

$userId = $_SESSION["user"]["id"];
$pdo = new PDO("sqlite:SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$contest_id = isset($_GET['contest_id']) ? (int)$_GET['contest_id'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // 1ページあたりの結果数
$offset = ($page - 1) * $perPage;

try {
    // 合計結果数を取得
    $totalStmt = $pdo->prepare("SELECT COUNT(*) FROM contest_answer WHERE user_id = :user_id AND contest_id = :contest_id");
    $totalStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $totalStmt->bindParam(':contest_id', $contest_id, PDO::PARAM_INT);
    $totalStmt->execute();
    $totalResults = $totalStmt->fetchColumn();
    $totalPages = ceil($totalResults / $perPage);

    // ユーザーの提出結果と問題のタイトルを取得
    $answerStmt = $pdo->prepare("SELECT a.*, q.question_order, q.question_title FROM contest_answer a INNER JOIN contest_question q ON a.question_id = q.id WHERE a.user_id = :user_id AND a.contest_id = :contest_id ORDER BY a.submitted_at DESC LIMIT :perPage OFFSET :offset");
    $answerStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $answerStmt->bindParam(':contest_id', $contest_id, PDO::PARAM_INT);
    $answerStmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $answerStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $answerStmt->execute();
    $answers = $answerStmt->fetchAll(PDO::FETCH_ASSOC);
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
            <th>提出時間</th>
                <th>問題番号</th>
                <th>問題タイトル</th>
                <th>回答</th>
                <th>正解</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($answers as $answer): ?>
                <tr>
                <td><?php echo htmlspecialchars($answer['submitted_at']); ?></td>
                    <td><?php echo htmlspecialchars($answer['question_order']); ?></td>
                    <td><?php echo htmlspecialchars($answer['question_title']); ?></td>
                    <td><?php echo htmlspecialchars($answer['answer_text']); ?></td>
                    <td><?php echo $answer['is_correct'] ? '正解' : '不正解'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?contest_id=<?php echo $contest_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>
