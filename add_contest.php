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

// フォームが送信された場合の処理
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

        // 登録後に別のページにリダイレクト
        header("Location: edit_contest.php");
        exit;
    } catch(PDOException $e) {
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
