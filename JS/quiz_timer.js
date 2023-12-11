let elapsedTime = 0; // 経過時間を記録する変数
const intervalTime = 1000; // 更新間隔（1秒）
const totalTime = 30000; // 合計時間（30秒）

// 1秒ごとに経過時間を更新して表示する
const intervalId = setInterval(function() {
    elapsedTime += intervalTime;

    // 残り時間（秒）を計算して表示
    let remainingTime = (totalTime - elapsedTime) / 1000;
    document.getElementById('remainingTime').textContent = '残り時間：' + remainingTime + '秒';

    // 30秒経過したらタイマーを停止してフォームを送信
    if (elapsedTime >= totalTime) {
        clearInterval(intervalId);
        document.getElementById('quizForm').submit();
    }
}, intervalTime);
