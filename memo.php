<?php
session_start();
// ...

$pdo = new PDO("sqlite:SQL/quiz.sqlite");
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
        $baseDir = "contest_question";
        if (!file_exists($baseDir)) {
            mkdir($baseDir);
        }

        // コンテスト専用のディレクトリを作成
        $contestDir = $baseDir . "/id_" . $contest_id . "_" . preg_replace("/[^A-Za-z0-9]/", '', $name);
        if (!file_exists($contestDir)) {
            mkdir($contestDir);
        }

        // タスクスケジューラの設定
        // コンテスト終了後のタスクスケジューリング
        $contest_end_time = strtotime($end_time) + 10 * 60; // コンテスト終了時間 + 10分
        $run_time = date('Y-m-d H:i', $contest_end_time);

        // ユニークなタスク名を作成
        $taskName = "UpdateRatingAfterContest" . $contest_id;

        // Schtasks コマンド
        $command = "Schtasks /Create /SC ONCE /TN \"" . $taskName . "\" /TR \"php path\\to\\your\\script.php " . $contest_id . "\" /ST $run_time";

        exec($command, $output, $return_var);
        if ($return_var === 0) {
            echo "タスクがスケジュールされました。";
        } else {
            echo "タスクのスケジューリングに失敗しました。";
        }

        header("Location: edit_contest.php");
        exit;
    } catch(PDOException $e) {
        echo "コンテスト作成に失敗しました: " . $e->getMessage();
    }
}
?>
