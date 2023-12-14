<p><a href="/Quiz/home.php">ホーム</a></p>
<p><a href="/Quiz/client/answer/start.php">クイズを解く</a></p>
<p><a href="/Quiz/client/post/post.php">投稿する</a></p>
<p><a href="/Quiz/client/contest/contest.php">コンテスト</a></p>
<p><a href="/Quiz/client/ranking/ranking.php">ランキング</a></p>
<?php
if(isset($_SESSION["user"])) {
    // ユーザ認証済みのときの処理
    print '<p><a href="/Quiz/client/profile/profile.php?userId='.h($_SESSION["user"]["id"]).'">'.h($_SESSION["user"]["name"]).'</a></p>';
    print '<p>[<a href="/Quiz/login/logout.php">ログアウト</a>]</p>';
} else {
    // 未認証のときの処理
    print '<p>[<a href="/Quiz/login/login_form.html">ログイン</a>]</p>';
}
?>
