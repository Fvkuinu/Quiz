<?php
  session_start();

  $_SESSION = array();
  session_destroy();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login success</title>
    <link rel="stylesheet" href="CSS/style.css">

  </head>
  <body>
      <h2>ログアウトしました</h2>
      <p><a href="home.php">ホームに戻る</a></p>
  </body>
</html>