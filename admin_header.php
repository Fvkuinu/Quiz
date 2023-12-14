<p><a href="admin_panel.php">adminトップへ</a></p>
<?php
if (isset($_SESSION["user"])) {
    //ユーザ認証済みのときの処理
    print '<p>[<a href="logout.php">ログアウト</a>]</p>';
} else {
    //未認証のときの処理
    print '<p>[<a href="login_form.php">ログイン</a>]</p>';
}
?>