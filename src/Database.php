<?php

declare(strict_types=1);

class Database
{
    private ?PDO $connection = null;
    private string $host;
    private string $name;
    private string $user;
    private string $password;

    public function __construct(
        string $host,
        string $name,
        string $user,
        string $password
    ) {
        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
    }

    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";

            $this->connection = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false
            ]);
        }

        return $this->connection;
    }
}