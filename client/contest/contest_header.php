<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <?php include('../../header.php'); ?>

    <a class="btn-vertical-border" href="/Quiz/client/contest/detail/contest_detail.php?contest_id=<?php echo $contest_id ?>">コンテストトップ</a>
    <a class="btn-vertical-border" href="/Quiz/client/contest/question/contest_questions.php?contest_id=<?php echo $contest_id ?>">問題</a>
    <a class="btn-vertical-border" href="/Quiz/client/contest/question/contest_submissions.php?contest_id=<?php echo $contest_id ?>">提出結果</a>
    <a class="btn-vertical-border" href="/Quiz/client/contest/explanation/contest_explanations.php?contest_id=<?php echo $contest_id ?>">解説</a>
    <a class="btn-vertical-border" href="/Quiz/client/contest/ranking/contest_ranking.php?contest_id=<?php echo $contest_id ?>">ランキング</a>
    

</body>

</html>

