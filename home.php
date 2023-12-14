<?php
session_start();

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
date_default_timezone_set("Asia/Tokyo");
$pdo = new PDO("sqlite:SQL/quiz.sqlite");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$st = $pdo->query("SELECT * FROM information ORDER BY id DESC LIMIT 10"); // 最新の10件のみ取得
$data = $st->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>home</title>
  <link rel="stylesheet" href="CSS/style.css">

</head>

<body>
<?php
include 'header.php';
?>


  <?php
  foreach($data as $info) {
    print '<div>';
    print '<p>投稿日:'.h($info["created_at"]);
    print '<h2>'.h($info["title"]).'</h2>';
    print '<p>'.h($info["content"]).'</p>';
    print '<p>最終更新日:'.h($info["updated_at"]);
    print '</div>';
  }
  ?>
  <p>
    <a href="./client/information/show_information.php">お知らせ一覧</a>
  </p>
</body>

</html>