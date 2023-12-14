<?php
session_start();

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

$perPage = 10; // 1ページあたりの表示件数
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

// データベース接続情報
$pdo = new PDO("sqlite:SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// 総ユーザー数を取得
$totalUsers = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();

// ユーザーをレーティング順に取得
$stmt = $pdo->prepare("SELECT * FROM user WHERE id != 1 ORDER BY rating DESC LIMIT :start, :perPage");
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include('header.php');

$rank = 1 + $start;
$prev_rating = null;
$same_rank_count = 1;

echo "<table>";
echo "<tr><th>順位</th><th>ユーザーネーム</th><th>Rating</th></tr>"; // ヘッダ行
foreach ($users as $row) {
    if ($prev_rating !== $row['rating']) {
        $rank += $same_rank_count;
        $same_rank_count = 1;
    } else {
        $same_rank_count++;
    }

    echo "<tr>";
    echo "<td>" . ($rank-1) . "</td>"; // 順位
    echo "<td><a href='profile.php?userId=" . $row['id'] . "'>" . h($row['username']) . "</a></td>"; // ユーザーネーム
    echo "<td>" . $row['rating'] . "</td>"; // Rating
    echo "</tr>";

    $prev_rating = $row['rating'];
}
echo "</table>";

// ページネーションのリンク
$totalPages = ceil($totalUsers / $perPage);
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='?page=" . $i . "'>" . $i . "</a> ";
}
?>
