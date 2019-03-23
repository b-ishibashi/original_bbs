<?php

namespace MyApp;

class Comment {

    public function __construct() {

        $this->create_token();
    }

    private function create_token() {

        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
        }
    }

    private function validate_token() {

        if (
            !isset($_SESSION['token']) ||
            !isset($_POST['token']) ||
            $_SESSION['token'] !== $_POST['token']
        ) {
            throw new \Exception('validate token!');
        }
    }

    public function post_comment() {

        try {

            //投稿不備検証
            $this->validate_ispost();

            $this->validate_post_type();

            $this->validate_token();

            $this->upload_comments();

            $_SESSION['success'] = 'Upload done!';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        //redirect
        //header('Location: http://' . $_SERVER['HTTP_HOST']);
        //exit;
    }

    public function get_results() {

        $success = null;
        $error = null;

        if(isset($_SESSION['success'])) {
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        if(isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        return [$success, $error];

    }

    public function get_name_comment() {

        $name = null;
        $comment = null;

        //入力情報検証
        if (array_key_exists('name', $_POST)) {
            $_SESSION['name'] = $_POST['name'];
            $name = $_SESSION['name'];
        }
        if (array_key_exists('comment', $_POST)) {
            $_SESSION['comment'] = $_POST['comment'];
            $comment = $_SESSION['comment'];
        }

        return [$name, $comment];
    }

    private function validate_ispost() {

        if ((!isset($_POST['name']) || $_POST['name'] === '') && (!isset($_POST['comment']) || $_POST['comment'] === '')) {
            throw new \Exception('何も入力されていません。');
        }

        if (!isset($_POST['name']) || $_POST['name'] === '') {
            throw new \Exception('名前が入力されていません。');
        }

        if (!isset($_POST['comment']) || $_POST['comment'] === '') {
            throw new \Exception('本文が入力されていません。');
        }
    }

    private function validate_post_type() {

        if (mb_strlen($_POST['name']) > 10) {
            throw new \Exception('名前は10文字以下にしてください。');
        }

        if (mb_strlen($_POST['comment']) > 100) {
            throw new \Exception('投稿は100文字以下にしてください。');
        }
    }

    private function upload_comments() {

        //データベースに接続
        $pdo = new \PDO(
            'mysql:dbname=testdb;host=localhost;charset=utf8mb4',
            'root',
            '',
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]
        );

        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $stmt =  $pdo->prepare("insert into users (name, comment) values (:name, :comment)");
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, \PDO::PARAM_STR);
        $stmt->execute();
    }

}







