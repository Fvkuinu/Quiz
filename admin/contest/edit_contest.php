<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login_form.php");
    exit;
}
if ($_SESSION["user"]["id"] != 1) {
    header("Location: home.php");
    exit;
}
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set("Asia/Tokyo");
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>admin_panel</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
    <p><a href="admin_panel.php">adminトップへ</a></p>
    <p><a href="add_contest.php">Add Contest</a></p>
    <?php
    // データベース接続情報
    $pdo = new PDO("sqlite:SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $perPage = 10; // 1ページあたりの表示件数
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

    try {
        // 総コンテスト数を取得
        $total = $pdo->query("SELECT COUNT(*) FROM contest")->fetchColumn();

        // コンテストの一覧を取得
        $stmt = $pdo->prepare("SELECT * FROM contest ORDER BY id DESC LIMIT :start, :perPage");
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        $contests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // コンテストの表示
        foreach ($contests as $contest) {
            echo "<div>\n";
            echo "<h2>" . h($contest['name']) . "</h2>\n";
            echo "<p>" . h($contest['description']) . "</p>\n";
            // 編集と削除と問題作成リンク
            echo "<a href='modify_contest.php?id=" . $contest['id'] . "'>Modify</a> | ";
            echo "<a href='delete_contest.php?id={$contest['id']}' onclick='return confirmDelete()'>Delete</a> | ";
            echo "<a href='add_contest_question.php?id=" . $contest['id'] . "'>Add Question</a> | ";
            echo "<a href='edit_contest_question.php?id=" . $contest['id'] . "'>Edit Question</a>";
            echo "</div>\n";
        }

        // ページネーションのリンク
        $totalPages = ceil($total / $perPage);
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='?page=" . $i . "'>" . $i . "</a> ";
        }

    } catch (PDOException $e) {
        echo 'エラー: ' . $e->getMessage();
    }
    ?>

    <!-- 削除するか確認用 -->
    <script>
        function confirmDelete() {
            return confirm("本当に削除しますか？");
        }
    </script>


</body>

</html>