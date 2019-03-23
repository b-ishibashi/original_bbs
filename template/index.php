<?php

session_start();

require_once(__DIR__ . '/../public/comment.php');
require_once(__DIR__ . '/../public/functions.php');

$comments = new MyApp\Comment;


//投稿された
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comments->post_comment();

    header('Location: http://' . $_SERVER['HTTP_HOST']);
    exit;
}
[$name, $comment] = $comments->get_name_comment();


[$success, $error] = $comments->get_results();
//var_dump($success);
//var_dump($error);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BBS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>BBS</h1>
    <?php if (isset($success)): ?>
        <div class="msg success"><?=h($success)?></div>
    <?php elseif (isset($error)): ?>
        <div class="msg error"><?=h($error)?></div>
    <?php else: ?>
        <div class="msg default">入力してください。</div>
    <?php endif; ?>
    <form action="" method="post" id="my_form">
        名前: <input type="text" name="name" value="<?= h($name) ?>" id="my_name">
        本文: <input type="text" name="comment" value="<?= h($comment) ?>" id="my_comment">
        <input type="hidden" name="token" value="<?= h($_SESSION['token']) ?>">
        <button type="submit" id="btn">投稿</button>
    </form>
</body>
</html>