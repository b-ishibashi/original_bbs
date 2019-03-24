<?php

use App\Http\Controllers\PostController;
use App\Http\Session\Session;

require_once __DIR__ . '/../app/Models/Post.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/Http/Session.php';
require_once __DIR__ . '/../app/Http/Controller/PostController.php';

$controller = new PostController(new Session());

//投稿された
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store($_REQUEST);
} else {
    $controller->index($_REQUEST);
}
