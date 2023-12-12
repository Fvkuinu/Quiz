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

$perPage = 10; // 1ページあたりの表示件数
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

try {
    // 総コンテスト数を取得
    $total = $pdo->query("SELECT COUNT(*) FROM contest")->fetchColumn();

    // コンテストの一覧を取得
    $stmt = $pdo->prepare("SELECT * FROM contest LIMIT :start, :perPage");
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $contests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // コンテストの表示
    foreach ($contests as $contest) {
        echo "<div>\n";
        echo "<h2>" . h($contest['name']) . "</h2>\n";
        echo "<p>" . h($contest['description']) . "</p>\n";
        // 編集と削除のリンク
        echo "<a href='edit_contest.php?id=" . $contest['id'] . "'>編集</a> | ";
        echo "<a href='delete_contest.php?id=" . $contest['id'] . "'>削除</a>";
        echo "</div>\n";
    }

    // ページネーションのリンク
    $totalPages = ceil($total / $perPage);
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=" . $i . "'>" . $i . "</a> ";
    }

} catch(PDOException $e) {
    echo 'エラー: ' . $e->getMessage();
}
?>
