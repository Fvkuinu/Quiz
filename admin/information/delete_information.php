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

// 指定されたIDの情報を取得して削除
$id = isset($_GET['id']) ? $_GET['id'] : '';
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM information WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // 削除後のリダイレクト
    header('Location: edit_information.php');
    exit;
}
echo '<p><a href="../admin_panel.php">adminトップへ</a></p>';
// IDが指定されていない場合のエラーメッセージ（またはエラーページへのリダイレクト）
echo "無効なIDです。";
?>
