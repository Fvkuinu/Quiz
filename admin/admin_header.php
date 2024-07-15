<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
<header>
  <!-- <div class="main-menu"> -->
    <!-- <div class="pc-menu">
      <ul>
        <li>
          <a href="/Quiz/home.php">HOME</a>
        </li>
        <li>
          <a href="/Quiz/client/answer/start.php">QUIZ</a>
        </li>
        <li>
          <a href="/Quiz/client/post/post.php">POST</a>
        </li>
        <li>
          <a href="/Quiz/client/contest/contest.php">CONTEST</a>
        </li>
        <li>
          <a href="/Quiz/client/ranking/ranking.php">RANKING</a>
        </li> -->
        <!-- <?php
                if (isset($_SESSION["user"])) {
                    // ユーザ認証済みのときの処理

                    echo '<li class="sp-menu__item">
                            <a class="sp-menu__link" href="/Quiz/login/logout.php">LOG OUT</a>
                          </li>';
                } else {
                    // 未認証のときの処理
                    echo '<li class="sp-menu__item">
                            <a class="sp-menu__link" href="/Quiz/login/login_form.html">LOG IN</a>
                          </li>';
                }
                ?> -->

            <!-- <div class="gnavi__wrap">
                <ul class="gnavi__lists">
                    <li class="gnavi__list">
                            <a href="/Quiz/login/login_form.html">LOG IN</a>
                        <ul class="dropdown__lists">
                            <li class="dropdown__list"><a href="#">メニュー1</a></li>
                            <li class="dropdown__list"><a href="#">メニュー1</a></li>
                            <li class="dropdown__list"><a href="#">メニュー1</a></li>
                        </ul>
                 </li>
        
             </ul>
            </div> -->
      <!-- </ul>
    </div> -->
   <!-- </div> -->
</header>

    <div class="sp-menu">
    <div class="close-button"></div>
        <input type="checkbox" id="sp-menu__check">
        <label for="sp-menu__check" class="sp-menu__box">
            <span></span>
        </label>

        <div class="sp-menu__content">
            <ul class="sp-menu__list">
                <li class="sp-menu__item">
                    <a class="sp-menu__link" href="/Quiz/admin/admin_panel.php">adminトップへ</a>
                </li>

                <?php
                if (isset($_SESSION["user"])) {
                    // ユーザ認証済みのときの処理

                    echo '<li class="sp-menu__item">
                            <a class="sp-menu__link" href="/Quiz/login/logout.php">LOG OUT</a>
                          </li>';
                } else {
                    // 未認証のときの処理
                    echo '<li class="sp-menu__item">
                            <a class="sp-menu__link" href="/Quiz/login/login_form.html">LOG IN</a>
                          </li>';
                }
                ?>

            </ul>
        </div>
    </div>

 </div>
             
<script src="/Quiz/JS/jquery-3.7.1.min.js"></script>

<script src="JS/main.js"></script>

</body>
</html>
