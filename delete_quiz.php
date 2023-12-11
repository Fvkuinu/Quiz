
<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login_form.php");
    exit;
}
if (!$_SESSION["user"]["id"] == 1) {
    header("Location: home.php");
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
$pdo = new PDO("sqlite:SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM question WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header('Location: edit_quiz.php');
    exit;
}

echo "無効なIDです。";
?>
