<?php

session_start();

require_once __DIR__ . '/../app/Models/Post.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/Http/Controller/PostController.php';

$controller = new \App\Http\Controllers\PostController($_SESSION);

//投稿された
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store($_REQUEST);
} else {
    $controller->index($_REQUEST);
}

include __DIR__ . '/../resources/views/index.php';
