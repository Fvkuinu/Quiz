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
// データベースへの接続
$pdo = new PDO("sqlite:SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

include 'database_config.php'; // データベース設定をインクルード

$contest_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($contest_id <= 0) {
    die("無効なコンテストIDです。");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_order = $_POST['question_order'];
    $question_title = $_POST['question_title'];
    $point = $_POST['point'];
    $correct_answer = $_POST['correct_answer'];
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    try {
        // コンテスト問題をデータベースに追加
        $stmt = $pdo->prepare("INSERT INTO contest_question (contest_id, question_order, question_title, point, correct_answer, created_at, updated_at) VALUES (:contest_id, :question_order, :question_title, :point, :correct_answer, :created_at, :updated_at)");
        $stmt->bindParam(':contest_id', $contest_id);
        $stmt->bindParam(':question_order', $question_order);
        $stmt->bindParam(':question_title', $question_title);
        $stmt->bindParam(':point', $point);
        $stmt->bindParam(':correct_answer', $correct_answer);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':updated_at', $updated_at);
        $stmt->execute();

        // contest_question ディレクトリの確認と作成
        $baseDir = "contest_question";
        if (!file_exists($baseDir)) {
            mkdir($baseDir);
        }
        // コンテスト名を取得するためにコンテスト情報を取得
        $contestStmt = $pdo->prepare("SELECT name FROM contest WHERE id = :contest_id");
        $contestStmt->bindParam(':contest_id', $contest_id);
        $contestStmt->execute();
        $contestData = $contestStmt->fetch(PDO::FETCH_ASSOC);
        $contestName = $contestData['name'];
        // コンテスト専用ディレクトリの作成
        $contestDirName = $baseDir . "/" . $contest_id . "_" . $contestName;
        if (!file_exists($contestDirName)) {
            mkdir($contestDirName);
        }

        // PHPファイルの保存パス
        $phpFilePath = $contestDirName . "/" . "question_" . $question_order . ".php";
        $phpContent .= "<html>\n<head>\n<title>" . h($question_title) . "</title>\n</head>\n<body>\n";
        $phpContent .= "<?php\ninclude 'header.php';\n?>\n";
        $phpContent .= "<h1>" . h($question_title) . "</h1>\n";
        $phpContent .= "<form method='get' action='contest_submit.php'>\n";
        $phpContent .= "<input type='hidden' name='contest_id' value='" . $contest_id . "'>\n";
        $phpContent .= "<input type='hidden' name='question_order' value='" . $question_order . "'>\n";
        $phpContent .= "<p><label for='answer'>あなたの回答:</label></p>\n";
        $phpContent .= "<p><input type='text' name='answer' id='answer' required></p>\n";
        $phpContent .= "<p><input type='submit' value='回答を送信'></p>\n";
        $phpContent .= "</form>\n";
        $phpContent .= "</body>\n</html>";

        // PHPファイルの生成
        file_put_contents($phpFilePath, $phpContent);

        echo "問題が追加され、HTMLファイルが生成されました。";
    } catch (PDOException $e) {
        echo "問題の追加に失敗しました: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>問題追加</title>
</head>

<body>
    <h2>コンテストへの問題追加 - コンテストID:
        <?php echo $contest_id; ?>
    </h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?id=' . $contest_id); ?>" method="post">
        <p>
            <label for="question_order">問題の順番:</label>
            <input type="number" name="question_order" id="question_order" required>
        </p>
        <p>
            <label for="question_title">問題のタイトル:</label>
            <input type="text" name="question_title" id="question_title" required>
        </p>
        <p>
            <label for="point">点数:</label>
            <input type="number" name="point" id="point" required>
        </p>
        <p>
            <label for="correct_answer">正解:</label>
            <input type="text" name="correct_answer" id="correct_answer" required>
        </p>
        <p>
            <input type="submit" value="問題を追加">
        </p>
    </form>
</body>

</html>