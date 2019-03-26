<?php

namespace App\Database;

class Connection
{

    protected $pdo;

    public static function get()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new static();
        }
        return $instance;
    }

    protected function __construct()
    {
        $this->pdo = new \PDO(
            'mysql:dbname=testdb;host=localhost',
            'root',
            '',
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]
        );
    }

    public function execute(string $sql, array $values = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $this->bindValues($stmt, $values);
        $stmt->execute();
        return $stmt;
    }

    protected function bindValue(\PDOStatement $stmt, array $values): void
    {
        foreach ($values as $value) {

        }
    }
}