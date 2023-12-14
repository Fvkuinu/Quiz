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
// データベースへの接続
$pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// 指定されたIDのコンテストを削除
$id = isset($_GET['id']) ? $_GET['id'] : '';
if ($id) {
    // 関連するタスクスケジューラの削除
    $taskName = "UpdateRatingAfterContest" . $id;
    $command = "Schtasks /Delete /TN \"" . $taskName . "\" /F";
    exec($command);

    // コンテストの削除
    $stmt = $pdo->prepare("DELETE FROM contest WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // 削除後に編集ページにリダイレクト
    header('Location: edit_contest.php');
    exit;
}

// IDが指定されていない場合のエラーメッセージ
echo '<p><a href="../admin_panel.php">adminトップへ</a></p>';
echo "無効なIDです。";
?>
