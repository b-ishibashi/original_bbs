<?php

namespace App\Models;

class Comment
{
    public function __construct()
    {
        $this->create_token();
    }

    private function create_token()
    {
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
        }
    }

    private function validate_token()
    {
        if (
            !isset($_SESSION['token']) ||
            !isset($_POST['token']) ||
            $_SESSION['token'] !== $_POST['token']
        ) {
            throw new \Exception('validate token!');
        }
    }

    private function memoize_inputs()
    {
        $_SESSION['name'] = $_POST['name'] ?? null;
        $_SESSION['comment'] = $_POST['comment'] ?? null;
    }

    private function clear_inputs()
    {
        $_SESSION['name'] = null;
        $_SESSION['comment'] = null;
    }

    public function create_post()
    {
        try {
            $this->memoize_inputs();

            //投稿不備検証
            $this->validate_name();

            $this->validate_comment();

            $this->validate_token();

            $this->insert_post();

            $_SESSION['success'] = 'Upload done!';

            $this->clear_inputs();
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        //redirect
        //header('Location: http://' . $_SERVER['HTTP_HOST']);
        //exit;
    }

    public function get_results()
    {
        $success = null;
        $error = null;

        if (isset($_SESSION['success'])) {
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        return [$success, $error];
    }

    public function get_name_comment()
    {
        return [
            $_SESSION['name'] ?? null,
            $_SESSION['comment'] ?? null,
        ];
    }

    private function validate_name()
    {
        if ((!isset($_SESSION['name']) || $_SESSION['name'] === '') && (!isset($_SESSION['comment']) || $_SESSION['comment'] === '')) {
            throw new \Exception('何も入力されていません。');
        }

        if (!isset($_SESSION['name']) || $_SESSION['name'] === '') {
            throw new \Exception('名前が入力されていません。');
        }

        if (mb_strlen($_SESSION['name']) > 10) {
            throw new \Exception('名前は10文字以下にしてください。');
        }
    }

    private function validate_comment()
    {
        if (!isset($_SESSION['comment']) || $_SESSION['comment'] === '') {
            throw new \Exception('本文が入力されていません。');
        }

        if (mb_strlen($_SESSION['comment']) > 20) {
            throw new \Exception('投稿は20文字以下にしてください。');
        }
    }

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

    public function get_posts()
    {

        //データベースから取得
        $pdo = new \PDO(
            'mysql:dbname=testdb;host=localhost;charset=utf8mb4',
            'root',
            '',
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]
        );

        $stmt = $pdo->prepare('select name, comment, created from posts order by id DESC');
        $stmt->execute();
        $comments = $stmt->fetchAll();

        return $comments;
    }
}
