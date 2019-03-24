<?php

namespace App\Models;

class Comment
{
    private function insert_post()
    {

        //データベースに接続
        $pdo = new \PDO(
            'mysql:dbname=testdb;host=localhost;charset=utf8mb4',
            'root',
            '',
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]
        );

        $name = $_SESSION['name'];
        $comment = $_SESSION['comment'];
        $stmt =  $pdo->prepare("insert into posts (name, comment, created) values (:name, :comment, now())");
        $stmt->bindValue(':name', $name, \PDO::PARAM_STR);
        $stmt->bindValue(':comment', $comment, \PDO::PARAM_STR);
        $stmt->execute();
    }
}
