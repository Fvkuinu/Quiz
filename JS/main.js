document.querySelector('.close-button').addEventListener('click', function () {
    document.getElementById('sp-menu__check').checked = false;
});
// ウィンドウのサイズを取得する関数
function getWindowSize() {
    return {
        width: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
        height: window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
    };
}

// ウィンドウが分割表示されているかどうかを取得する関数
function isSplitScreen() {
    return window.matchMedia('(min-width: 768px) and (max-width: 1024px)').matches;
    // この条件は適宜変更してください。ここでは、画面幅が768px以上かつ1024px以下である場合を分割表示と見なしています。
}

// ウィンドウのサイズを表示
function displayWindowSize() {
    var size = getWindowSize();
    console.log('Window Size:', size.width, 'x', size.height);
}

// 分割表示の状態を表示
function displaySplitScreenState() {
    var splitScreen = isSplitScreen();
    console.log('Is Split Screen:', splitScreen);
}

// ウィンドウのリサイズ時にサイズを表示
window.addEventListener('resize', displayWindowSize);

// 初期表示時にサイズと分割表示の状態を表示
window.addEventListener('DOMContentLoaded', function() {
    displayWindowSize();
    displaySplitScreenState();
});
window.addEventListener('DOMContentLoaded', function() {
// スマホメニューとPCメニューの要素を取得
var spMenu = document.querySelector('.sp-menu');
var pcMenu = document.querySelector('.pc-menu');

// メニューを制御する関数
function toggleMenu() {
  var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

  // ウィンドウの幅が500以下かどうかを判定
  if (windowWidth <= windowwidth) {// 元の値は625
    spMenu.style.display = 'block';  // スマホメニューを表示
    pcMenu.style.display = 'none';   // PCメニューを非表示
  } else {
    spMenu.style.display = 'none';   // スマホメニューを非表示
    pcMenu.style.display = 'block';  // PCメニューを表示
  }
}

// ウィンドウサイズが変更されたらメニューを切り替える
window.addEventListener('resize', toggleMenu);

// 初回表示時にもメニューを切り替える
toggleMenu();
});


