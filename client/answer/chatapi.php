<?php

// ChatGPT APIレスポンス処理

// API_KEYをセット
define('API_KEY', 'sk-');

// ChatGPT APIへのリクエスト構築
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . API_KEY));

// ここにChatGPTに送信するオプションを設定
$data = array('model' => 'gpt-4-1106-preview');
$data["messages"] = array();

// ここにAIのキャラクター設定を設定
$data["messages"][] = array('role' => 'system', 'content' => "必ずjsonのみで出力する。問題の内容は地理、歴史、音楽、スポーツから選ぶ。jsonのキーはQ:問題,A:答え。出力形式は{\"Q\":問題,\"A\":答え}");
$data["messages"][] = array('role' => 'user', 'content' => "日本語で楽しい問題を1つ作って");

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$result = curl_exec($ch);

// HTTPステータスコードを取得
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// エラーハンドリング
if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    echo "<h1>エラーが発生しました: $error_msg</h1>";
    curl_close($ch);
    exit;
}

$response = json_decode($result, true);
curl_close($ch);


// JSONを配列にデコード
$a = $response["choices"][0]["message"]["content"];
$a = mb_convert_encoding($a, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$data = json_decode($a, true);
// Q（質問）とA（答え）を取得
$question = $data['Q'];
$answer = $data['A'];

// 結果を表示
//echo "質問: " . $question . "<br>";
//echo "答え: " . $answer;


try {
    // SQLite データベースへの接続を確立
    $pdo = new PDO("sqlite:../../SQL/quiz.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    // 新しいデータを挿入するための準備
    $query = "INSERT INTO question (question, answer, writer_id) VALUES (:question, :answer, :writer_id)";
    $stmt = $pdo->prepare($query);

    // ここで値を設定
    $writer_id = 1; //ライターのID

    // バインドパラメータを設定
    $stmt->bindParam(':question', $question);
    $stmt->bindParam(':answer', $answer);
    $stmt->bindParam(':writer_id', $writer_id);

    // SQL文を実行
    $stmt->execute();
    if ($httpcode == 200) {
        header("Location: solve.php");
        exit;
    }

    //echo "新しいレコードが追加されました。";

} catch (PDOException $e) {
    // エラー処理
    //echo "データベースエラー: " . $e->getMessage();
}


// レスポンスとHTTPステータスコードを出力
//echo "<h2>HTTPステータスコード: $httpcode</h2>";
//$res = str_replace("\n","<br>", $response["choices"][0]["message"]["content"]);
//print $res;

?>