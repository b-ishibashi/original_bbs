<?php

namespace App\Http\Session;

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
}
