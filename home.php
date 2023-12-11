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
  <p><a href="home.php">ホーム</a></p>
  <p><a href="start.php">クイズを解く</a></p>
  <p><a href="post.php">投稿する</a></p>

  <?php
  
  if(isset($_SESSION["user"])) {
    //ユーザ認証済みのときの処理
    print '<p><a href="profile.php?userId='.$_SESSION["user"]["id"].'">'.h($_SESSION["user"]["name"]).'</a></p>';
    print '<p>[<a href="logout.php">ログアウト</a>]</p>';
  } else {
    //未認証のときの処理
    print '<p>[<a href="login_form.php">ログイン</a>]</p>';
  }
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
    <a href="show_information.php">お知らせ一覧</a>
  </p>
  <p>
    <a href="post.php">問題投稿</a>
  </p>
</body>

</html>