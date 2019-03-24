<?php

namespace App\HTTP\Controllers;

class CommentController
{
    /**
     * @var array
     */
    protected $session;

    /**
     * インスタンス作成
     *
     * @param array $session $_SESSION を渡す（参照渡し）
     */
    public function __construct(array &$session)
    {
        $this->session = $session;
        $this->create_token();
    }

    /**
     * 一覧表示
     *
     * @param array $request $_REQUEST を渡す（値渡し）
     */
    public function index(array $request)
    {

    }

    /**
     * 新規作成
     *
     * @param array $request $_REQUEST を渡す（値渡し）
     */
    public function store(array $request)
    {
        try {
            $this->memoize_inputs($request);

            //投稿不備検証
            $this->validate_name();

            $this->validate_comment();

            $this->validate_token($request);

            $this->insert_post();

            $this->session['success'] = 'Upload done!';

            $this->clear_inputs();
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        //redirect
        //header('Location: http://' . $_SERVER['HTTP_HOST']);
        //exit;
    }

    private function create_token()
    {
        if (!isset($this->session)) {
            $this->session['token'] = bin2hex(openssl_random_pseudo_bytes(16));
        }
    }

    private function validate_token($request)
    {
        if (
            !isset($this->session['token']) ||
            !isset($request['token']) ||
            $this->session['token'] !== $request['token']
        ) {
            throw new \Exception('validate token!');
        }
    }

    private function memoize_inputs($request)
    {
        $this->session['name'] = $request['name'] ?? null;
        $this->session['comment'] = $request['comment'] ?? null;
    }

    private function clear_inputs()
    {
        $this->session['name'] = null;
        $this->session['comment'] = null;
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
        if ((!isset($this->session['name']) || $this->session['name'] === '') && (!isset($this->session['comment']) || $this->session['comment'] === '')) {
            throw new \Exception('何も入力されていません。');
        }

        if (!isset($this->session['name']) || $this->session['name'] === '') {
            throw new \Exception('名前が入力されていません。');
        }

        if (mb_strlen($this->session['name']) > 10) {
            throw new \Exception('名前は10文字以下にしてください。');
        }
    }

    private function validate_comment()
    {
        if (!isset($this->session['comment']) || $this->session['comment'] === '') {
            throw new \Exception('本文が入力されていません。');
        }

        if (mb_strlen($this->session['comment']) > 20) {
            throw new \Exception('投稿は20文字以下にしてください。');
        }
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