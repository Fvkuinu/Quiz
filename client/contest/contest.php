<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../../login/login_form.html");
    exit;
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set("Asia/Tokyo");
// データベース接続情報
$pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// ページネーション変数
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 10; // 1ページあたりの表示数
$offset = ($page > 1) ? ($page * $perPage) - $perPage : 0;

try {
    // コンテストの総数を取得
    $total = $pdo->query("SELECT COUNT(*) FROM contest")->fetchColumn();
    $pages = ceil($total / $perPage);

    // コンテスト一覧を取得
    $stmt = $pdo->prepare("SELECT * FROM contest LIMIT :perPage OFFSET :offset");
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $contests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("エラー: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>コンテスト一覧</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php include('../../header.php'); ?>
    <h1>コンテスト一覧</h1>
    <ul>
        <?php foreach ($contests as $contest): ?>
            <li>
                <a href="./detail/contest_detail.php?contest_id=<?php echo h($contest['id']); ?>">
                    <?php echo h($contest['name']); ?>
                </a>
                <br>
                開始時間:
                <?php echo h($contest['start_time']); ?>
                <br>
                終了時間:
                <?php echo h($contest['end_time']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div>
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
</body>

</html>