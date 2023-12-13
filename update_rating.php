<?php
$pdo = new PDO("sqlite:C:\MAMP\htdocs\Quiz\SQL\quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$contest_id = $argv[1]; // コマンドライン引数からの場合
//$contest_id = 1;
try {
    // コンテストの正解データを取得
    $stmt = $pdo->prepare("
        SELECT ca.user_id, SUM(cq.point) AS total_score
        FROM (
            SELECT ca.user_id, ca.question_id, MIN(ca.id) AS min_id
            FROM contest_answer ca
            INNER JOIN contest_question cq ON cq.id = ca.question_id
            WHERE ca.contest_id = :contest_id AND ca.is_correct = 1
            GROUP BY ca.user_id, cq.question_order
        ) AS first_correct_answers
        INNER JOIN contest_answer ca ON ca.id = first_correct_answers.min_id
        INNER JOIN contest_question cq ON cq.id = ca.question_id
        GROUP BY ca.user_id
    ");
    $stmt->bindParam(':contest_id', $contest_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 各ユーザーのレートを更新
    foreach ($results as $result) {
        $user_id = $result['user_id'];
        $total_score = $result['total_score'];

        // 現在のレートを取得
        $currentRatingStmt = $pdo->prepare("SELECT rating FROM user WHERE id = :user_id");
        $currentRatingStmt->bindParam(':user_id', $user_id);
        $currentRatingStmt->execute();
        $currentRatingResult = $currentRatingStmt->fetch(PDO::FETCH_ASSOC);
        $current_rating = $currentRatingResult ? $currentRatingResult['rating'] : 0;

        // レートの計算
        $new_rating = calculateNewRating($total_score, $current_rating);

        // ユーザーのレートを更新
        $updateStmt = $pdo->prepare("UPDATE user SET rating = :rating WHERE id = :user_id");
        $updateStmt->bindParam(':rating', $new_rating);
        $updateStmt->bindParam(':user_id', $user_id);
        $updateStmt->execute();
    }

    echo "ユーザーのレートが更新されました。";

} catch (PDOException $e) {
    echo 'エラー: ' . $e->getMessage();
}

function calculateNewRating($total_score, $current_rating)
{
    // レート計算ロジックの実装（スコアと現在のレートを考慮）
    return $current_rating + $total_score;
}
?>