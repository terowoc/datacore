<?php

namespace Terowoc\DataCore;

use \PDO;
use \Terowoc\DataCore\Table;

class DataBase
{
    public \PDO $pdo;
    public Table $table;

    public function __construct(
        private string $database,
        private string $username = 'postgres',
        private string $password = '1234',
        private string $host = '127.0.0.1',
        private int $port = 5432,
        private string $driver = 'pgsql',
        private string $charset = 'utf8'
    ) {
        $this->connect();
    }

    private function connect(): void
    {
        $dsn = $this->driver . ":host=" . $this->host . ";dbname=" . $this->database . ";port=" . $this->port . ";";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new \PDO($dsn, $this->username, $this->password, $options);
            $this->table = new Table($this);
        } catch (\PDOException $e) {
            exit("Database connection failed: {$e->getMessage()}");
        }
    }

    public function getVersion(): string
    {
        return $this->pdo->query('select version()')->fetchColumn();
    }

    public function getDriverName(): string
    {
        return $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }
}
