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
<?php
session_start();


$pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    try {
        $stmt = $pdo->prepare("INSERT INTO contest (name, description, start_time, end_time, created_at, updated_at) VALUES (:name, :description, :start_time, :end_time, :created_at, :updated_at)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':updated_at', $updated_at);
        $stmt->execute();

        $contest_id = $pdo->lastInsertId();

        // contest_question ディレクトリを確認し、必要に応じて作成
        $baseDir = "../../contest_question";
        if (!file_exists($baseDir)) {
            mkdir($baseDir);
        }

        // コンテスト専用のディレクトリを作成
        $contestDir = $baseDir . "/" . $contest_id . "_" . $name;
        if (!file_exists($contestDir)) {
            mkdir($contestDir);
        }



        // 解説のPHPファイルの保存パス
        $phpFilePath = $contestDir . "/detail.html";
        $phpContent .= "<html>\n<head>\n<title>" . h($description) . "</title>\n</head>\n<body>\n";
        $phpContent .= "<h1>" . h($description) . "</h1>\n";
        $phpContent .= "<!-- ここにコンテスト詳細を書く -->\n";
        $phpContent .= "</body>\n</html>";
        
        // PHPファイルの生成
        file_put_contents($phpFilePath, $phpContent);
        // タスクスケジューラの設定
        // コンテスト終了後のタスクスケジューリング
        $contest_end_time = strtotime($end_time); //+ 10 * 60; // コンテスト終了時間 + 10分

        // 日付と時間を別々に取得
        $run_date = date('Y-m-d', $contest_end_time);
        $run_time = date('H:i', $contest_end_time);

        // ユニークなタスク名を作成
        $taskName = "UpdateRatingAfterContest" . $contest_id;

        // Schtasks コマンド（日付と時間を別々に指定）
        $command = "Schtasks /Create /SC ONCE /TN \"" . $taskName . "\" /TR \"C:\\MAMP\\bin\\php\\php8.1.0\\php.exe C:\\MAMP\\htdocs\\Quiz\\rating\\update_rating.php " . $contest_id . "\" /SD $run_date /ST $run_time
        ";


        exec($command, $output, $return_var);
        if ($return_var === 0) {
            echo "タスクがスケジュールされました。";
        } else {
            echo "タスクのスケジューリングに失敗しました。";
        }

        //header("Location: edit_contest.php");
        //exit;
    } catch (PDOException $e) {
        echo "コンテスト作成に失敗しました: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>コンテスト作成フォーム</title>
</head>

<body>
<?php include('../admin_header.php'); ?>
    <form action="<?php echo h($_SERVER["PHP_SELF"]); ?>" method="post">
        <p>
            <label for="name">コンテスト名:</label>
            <input type="text" name="name" id="name" required>
        </p>
        <p>
            <label for="description">説明:</label>
            <textarea name="description" id="description"></textarea>
        </p>
        <p>
            <label for="start_time">開始時間:</label>
            <input type="datetime-local" name="start_time" id="start_time" required>
        </p>
        <p>
            <label for="end_time">終了時間:</label>
            <input type="datetime-local" name="end_time" id="end_time" required>
        </p>
        <p>
            <input type="submit" value="コンテスト作成">
        </p>
    </form>
</body>

</html>