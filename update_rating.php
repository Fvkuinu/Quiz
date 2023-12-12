<?php
$pdo = new PDO("sqlite:SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$contest_id = $argv[1]; // コマンドライン引数からの場合

try {
    // コンテストの正解データを取得
    $stmt = $pdo->prepare("
        SELECT ua.user_id, SUM(cq.point) as total_score
        FROM (
            SELECT user_id, question_order, MIN(id) as min_id
            FROM user_answer
            WHERE contest_id = :contest_id AND is_correct = 1
            GROUP BY user_id, question_order
        ) AS first_correct_answers
        JOIN user_answer ua ON ua.id = first_correct_answers.min_id
        JOIN contest_question cq ON cq.contest_id = ua.contest_id AND cq.question_order = ua.question_order
        GROUP BY ua.user_id
    ");
    $stmt->bindParam(':contest_id', $contest_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 各ユーザーのレートを更新
    foreach ($results as $result) {
        $user_id = $result['user_id'];
        $total_score = $result['total_score'];

        // 現在のレートを取得
        $currentRatingStmt = $pdo->prepare("SELECT rating FROM users WHERE user_id = :user_id");
        $currentRatingStmt->bindParam(':user_id', $user_id);
        $currentRatingStmt->execute();
        $currentRatingResult = $currentRatingStmt->fetch(PDO::FETCH_ASSOC);
        $current_rating = $currentRatingResult ? $currentRatingResult['rating'] : 0;

        // レートの計算
        $new_rating = calculateNewRating($total_score, $current_rating);

        // ユーザーのレートを更新
        $updateStmt = $pdo->prepare("UPDATE users SET rating = :rating WHERE user_id = :user_id");
        $updateStmt->bindParam(':rating', $new_rating);
        $updateStmt->bindParam(':user_id', $user_id);
        $updateStmt->execute();
    }

    echo "ユーザーのレートが更新されました。";

} catch(PDOException $e) {
    echo 'エラー: ' . $e->getMessage();
}

function calculateNewRating($total_score, $current_rating) {
    // レート計算ロジックの実装（スコアと現在のレートを考慮）
    return $current_rating + $total_score;
}
?>
