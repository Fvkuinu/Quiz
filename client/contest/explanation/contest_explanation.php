<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../../../login/login_form.php");
    exit;
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

date_default_timezone_set("Asia/Tokyo");
// データベース接続情報（以前の設定に基づく）
$pdo = new PDO("sqlite:../../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$contest_id = isset($_GET['contest_id']) ? (int)$_GET['contest_id'] : 0;
$question_order = isset($_GET['question_order']) ? (int)$_GET['question_order'] : 0;

try {
    // コンテスト名を取得
    $contestStmt = $pdo->prepare("SELECT name FROM contest WHERE id = :contest_id");
    $contestStmt->bindParam(':contest_id', $contest_id, PDO::PARAM_INT);
    $contestStmt->execute();
    $contest = $contestStmt->fetch(PDO::FETCH_ASSOC);

    if ($contest) {
        $contest_name = $contest['name'];
    } else {
        throw new Exception("コンテストが見つかりません。");
    }
    
    // 問題ファイルのパス
    $question_file = "../../../contest_question/{$contest_id}_{$contest_name}/explanation_{$question_order}.php";

    // 問題のHTMLファイルを読み込む
    if (file_exists($question_file)) {
        include($question_file);
    } else {
        throw new Exception("問題が見つかりません。".$question_file);
    }
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
}
?>
