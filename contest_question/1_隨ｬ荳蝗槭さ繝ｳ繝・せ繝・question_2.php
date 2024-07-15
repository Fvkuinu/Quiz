
<html>

<head>
    <title>Aがこたえです</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <h1>Aがこたえです</h1>
    <?php if (!empty($message)): ?>
        <p>
            <?php echo h($message); ?>
        </p>
    <?php endif; ?>
    <form method='get' action='contest_submit.php'>
        <input type='hidden' name='contest_id' value='1'>
        <input type='hidden' name='question_order' value='2'>
        <p><label for='answer'>あなたの回答:</label></p>
        <p><input type='text' name='answer' id='answer' required></p>
        <p><input type='submit' value='回答を送信'></p>
    </form>
</body>

</html>