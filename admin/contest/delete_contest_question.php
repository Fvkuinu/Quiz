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
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$question_id = isset($_GET['question_id']) ? (int) $_GET['question_id'] : 0;


try {
    // 削除するSQLクエリを準備
    $stmt = $pdo->prepare("DELETE FROM contest_question WHERE id = :question_id");
    $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);

    // SQLクエリの実行
    $stmt->execute();

    // 完了メッセージの表示、またはリダイレクト
    echo "問題が削除されました。";
    // header('Location: 問題一覧ページへのURL'); // 完了後に問題一覧ページにリダイレクトする場合

} catch (PDOException $e) {
    echo 'エラー: ' . $e->getMessage();
}

?>
