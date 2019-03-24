<?php

session_start();

require_once __DIR__ . '../app/Models/Comment.php';
require_once __DIR__ . '../app/functions.php';

$posts = new App\Models\Comment;


//投稿された
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posts->create_post();

    header('Location: /');
    exit;
}
[$name, $comment] = $posts->get_name_comment();


[$success, $error] = $posts->get_results();
//var_dump($success);
//var_dump($error);

//var_dump($posts->get_posts());
//exit;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BBS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="bs-mask">
        <div class="post_form">
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
        </div>
        <div class="post_list">
            <h1>投稿内容</h1>
            <article class="article">
               <?php foreach ($posts->get_posts() as $post): ?>
                    <div class="post">
                        <div id="now"><span>投稿日時:</span> <?= h($post['created']) ?></div>
                        <div id="name"><span>名前:</span> <?= h($post['name']) ?></div>
                        <div id="comment"><span>本文:</span> <?= h($post['comment']) ?></div>
                    </div>
               <?php endforeach; ?>
            </article>
        </div>
    </div>
</body>
</html>