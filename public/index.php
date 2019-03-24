<?php

session_start();

require_once __DIR__ . '/../app/Models/Comment.php';
require_once __DIR__ . '/../app/functions.php';

$posts = new App\Models\Comment;

$controller = new \App\HTTP\Controllers\CommentController($_SESSION);

//投稿された
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store($_REQUEST);
} else {
    $controller->index($_REQUEST);
}

//header('Location: /');
//exit;
//[$name, $comment] = $posts->get_name_comment();


//[$success, $error] = $posts->get_results();

include __DIR__ . '/../resources/views/index.php';
