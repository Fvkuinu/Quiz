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
$pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$contest_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    try {
        // まず既存のタスクを削除
        $oldTaskName = "UpdateRatingAfterContest" . $contest_id;
        $deleteCommand = "Schtasks /Delete /TN \"" . $oldTaskName . "\" /F";
        exec($deleteCommand);

        // コンテスト情報を更新
        $stmt = $pdo->prepare("UPDATE contest SET name = :name, description = :description, start_time = :start_time, end_time = :end_time WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':id', $contest_id);
        $stmt->execute();

        // タスクスケジューラの設定
        // コンテスト終了後のタスクスケジューリング
        $contest_end_time = strtotime($end_time); //+ 10 * 60; // コンテスト終了時間 + 10分

        // 日付と時間を別々に取得
        $run_date = date('Y-m-d', $contest_end_time);
        $run_time = date('H:i', $contest_end_time);
        
        // ユニークなタスク名を作成
        $taskName = "UpdateRatingAfterContest" . $contest_id;
        
        // Schtasks コマンド（日付と時間を別々に指定）
        $command = "Schtasks /Create /SC ONCE /TN \"" . $taskName . "\" /TR \"php C:\\MAMP\\htdocs\\Quiz\\rating\\update_rating.php " . $contest_id . "\" /SD $run_date /ST $run_time";
        

        exec($command, $output, $return_var);
        if ($return_var === 0) {
            echo "<h2>タスクがスケジュールされました。</h2>";
            echo '<a href="edit_contest.php" class="btn-partial-line">  <i class="fa fa-caret-right"></i>コンテスト一覧へ戻る</a>';
        } else {
            echo "<h2>タスクのスケジューリングに失敗しました。</h2>";
            echo '<a href="edit_contest.php" class="btn-partial-line">  <i class="fa fa-caret-right"></i>コンテスト一覧へ戻る</a>';
        }
        

        // 更新後にリダイレクト
        //header("Location: edit_contest.php");
        //exit;
    } catch (PDOException $e) {
        echo "更新に失敗しました: " . $e->getMessage();
    }
} else {
    // コンテスト情報の取得
    $stmt = $pdo->prepare("SELECT * FROM contest WHERE id = :id");
    $stmt->bindParam(':id', $contest_id);
    $stmt->execute();
    $contest = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contest) {
        die("コンテストが見つかりません");
    }
    ?>

    <?php include('../admin_header.php'); ?>
    <h1>MODIFY CONTEST</h1>
    <!-- 編集フォーム -->
    <form action="<?php echo h($_SERVER["PHP_SELF"] . '?id=' . $contest_id); ?>" method="post">
            <h3>コンテスト名</h3>
            <input type="text" name="name" id="name" value="<?php echo h($contest['name']); ?>" required>

            <h3>説明:</h3>
            <textarea name="description"
                id="description"　required><?php echo h($contest['description']); ?></textarea>
  
                <h3>時刻設定</h3>
            <label for="start_time">開始時間:</label>
            <input type="datetime-local" name="start_time" id="start_time"
                value="<?php echo h($contest['start_time']); ?>" required><br>

            <label for="end_time">終了時間:</label>
            <input type="datetime-local" name="end_time" id="end_time"
                value="<?php echo h($contest['end_time']); ?>" required>

        <p>
            <input type="submit" value="更新" class="btn-square">
        </p>
    </form>
    <?php
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>コンテスト編集</title>
    <link rel="stylesheet" href="../..//CSS/style.css">
</head>

<body></body>

</html>