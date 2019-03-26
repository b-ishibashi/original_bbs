<?php

use App\Http\Controllers\PostController;
use App\Http\Session;

require_once __DIR__ . "/../vendor/autoload.php";

$controller = new PostController(new Session());

//投稿された
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store($_REQUEST);
} else {
    $controller->index($_REQUEST);
}
