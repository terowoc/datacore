<?php

namespace Terowoc\DataCore;

use Terowoc\DataCore\DataBase;

class Table
{
    public function __construct(
        private DataBase $db
    ) {

    }

    public function create(string $name, array $options)
    {

        $sql = "CREATE TABLE IF NOT EXISTS {$name}(";

        foreach ($options as $key => $value) {
            $sql .= "{$key} {$value}";
        }

        $sql .= ");";

        try {
            $this->db->pdo->query($sql);
        } catch (\PDOException $e) {
            return $e->getMessage();
        }

        return "Done! {$name} table created!";
    }

    public function drop(string $name): string
    {
        try {
            $this->db->pdo->query("DROP TABLE {$name};");
        } catch (\PDOException $e) {
            return $e->getMessage();
        }

        return "Done! {$name} table deleted!";
    }

    public function insert(string $table, array $data): string
    {
        $fields = array_keys($data);

        $columns = implode(', ', $fields);
        $binds = implode(', ', array_map(fn($field) => ":$field", $fields));

        try {
            $sql = "INSERT INTO $table ($columns) VALUES ($binds)";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->execute($data);
        } catch (\PDOException $e) {
            return $e->getMessage();
        }

        return 'Done!';
    }

    public function first(string $table, array $conditions = []): ?array
    {
        $where = '';

        if (count($conditions) > 0) {
            $where = 'WHERE ' . implode(' AND ', array_map(fn($field) => "{$field} = :{$field}", array_keys($conditions)));
        }

        try {
            $sql = "SELECT * FROM $table $where LIMIT 1";

            $stmt = $this->db->pdo->prepare($sql);
            $stmt->execute($conditions);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        return $result ?: null;
    }

    public function get(string $table, array $conditions = [], array $order = [], int $limit = -1): array
    {
        $where = '';

        if (count($conditions) > 0) {
            $where = 'WHERE ' . implode(' AND ', array_map(fn($field) => "{$field} = :{$field}", array_keys($conditions)));
        }

        $sql = "SELECT * FROM $table $where";

        if (count($order) > 0) {
            $sql .= ' ORDER BY ' . implode(', ', array_map(fn($field, $direction) => "{$field} {$direction}", array_keys($order), $order));
        }

        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
        }

        try {
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->execute($conditions);
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function update(string $table, array $data, array $conditions = []): string
    {
        $fields = array_keys($data);

        $set = implode(', ', array_map(fn($field) => "$field = :$field", $fields));

        $where = '';

        if (count($conditions) > 0) {
            $where = 'WHERE ' . implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($conditions)));
        }

        try {
            $sql = "UPDATE $table SET $set $where";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->execute(array_merge($data, $conditions));
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        return 'Done!';
    }

    public function delete(string $table, array $conditions = []): string
    {
        $where = '';

        if (count($conditions) > 0) {
            $where = 'WHERE ' . implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($conditions)));
        }

        try {
            $sql = "DELETE FROM $table $where";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->execute($conditions);
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        return 'Done!';
    }
}
