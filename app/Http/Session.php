<?php

namespace App\Http;

class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        // まだ session_start() を呼んでなかったら呼ぶ
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // tokenがなければ生成
        if (!$this->token()) {
            $this->regenerate_token();
        }

;
    }

    /**
     * 値の保存
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * 値の取得
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * 値の削除
     *
     * @param string $key
     */
    public function unset(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * 取得した後削除する
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function flash(string $key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->unset($key);
        return $value;
    }

    /**
     * トークン取得
     *
     * @return string
     *
     */

    public function token(): string
    {
        return $this->get('token');
    }

    /**
     *CSRFトークンの(再)作成
     *
     */
    public function regenerate_token(): void
    {
        $this->set('token', bin2hex(openssl_random_pseudo_bytes(16)));
    }

    /**
     * CSRFトークンの検証
     * 失敗したらスロー
     *
     */
    public function validate_token(array $request): void
    {
        // 期待する値
        $expected = $this->token();

        // 実際に送られてきた値
        $actual = $request['token'] ?? null;

        // 以下のいずれかの場合にエラーとする
        // ・期待する値が null である（トークンは普通設定されているのでバグではない限りあり得ない）
        // ・送られてきた値が null もしくは未定義である
        // ・期待する値と送られてきた値が一致しない
        if ($expected === null || $actual === null || $expected !== $actual) {
            throw new \Exception('invalidate token!');
        }
    }
}
