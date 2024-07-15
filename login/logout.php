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
    <link rel="stylesheet" href="../CSS/style.css">

  </head>
  <body>
      <h2>ログアウトしました</h2>
      <p><a href="../home.php" class="btn-partial-line"><i class="fa fa-caret-right"></i> ホームに戻る</a></p>
  </body>
</html>