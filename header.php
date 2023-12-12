<p><a href="home.php">ホーム</a></p>
<p><a href="start.php">クイズを解く</a></p>
<p><a href="post.php">投稿する</a></p>
<p><a href="contest.php">コンテスト</a></p>
<?php
if(isset($_SESSION["user"])) {
    // ユーザ認証済みのときの処理
    print '<p><a href="profile.php?userId='.h($_SESSION["user"]["id"]).'">'.h($_SESSION["user"]["name"]).'</a></p>';
    print '<p>[<a href="logout.php">ログアウト</a>]</p>';
} else {
    // 未認証のときの処理
    print '<p>[<a href="login_form.php">ログイン</a>]</p>';
}
?>
